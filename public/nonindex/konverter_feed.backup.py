import requests
import xml.etree.ElementTree as ET
from xml.dom import minidom
import sys
import os
import logging

# --- Configuration ---
SOURCE_URL = "https://zederkof.dk/eksport/utleiepartner.xml"
SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
OUTPUT_FILE = os.path.join(SCRIPT_DIR, "korrigert_feed.xml")
LOG_FILE = os.path.join(SCRIPT_DIR, "konvertering_logg.txt")

# --- Namespace Registration (VIKTIG ENDRING) ---
# Registrerer 'tax' navnerommet for å kunne finne taggen korrekt.
ET.register_namespace('tax', 'tax')

def setup_logging():
    """Konfigurerer logging til fil."""
    if not logging.getLogger().handlers:
        logging.basicConfig(
            level=logging.INFO,
            format='%(asctime)s - %(levelname)s - %(message)s',
            handlers=[
                logging.FileHandler(LOG_FILE),
                logging.StreamHandler()
            ]
        )

def get_child_text(parent_element, tag_name):
    """Safely gets text from a child element."""
    child = parent_element.find(tag_name)
    return child.text if child is not None and child.text else ""

def set_child_text(parent_element, tag_name, text):
    """Safely sets text for a child element."""
    child = parent_element.find(tag_name)
    if child is None:
        child = ET.SubElement(parent_element, tag_name)
    child.text = text

def run_conversion():
    """Main function to fetch, process, and save the converted XML feed."""
    logging.info("--- Starter konverteringsjobb ---")
    
    try:
        logging.info(f"Henter feed fra {SOURCE_URL}...")
        response = requests.get(SOURCE_URL, timeout=30)
        response.raise_for_status()
        xml_content = response.content.decode('utf-8-sig').encode('utf-8')
        logging.info("Feed hentet successfully.")

        logging.info("Parser XML-data...")
        root = ET.fromstring(xml_content)
        logging.info("XML parset successfully.")

        logging.info("Indekserer mor- og enkeltprodukter...")
        parents = {p.find('id').text: p for p in root.findall('Product') if p.find('post_parent') is None or not p.find('post_parent').text}
        logging.info(f"Fant {len(parents)} potensielle mor/enkelt-produkter.")

        logging.info("Prosesserer produkter og bygger ny feed...")
        final_products = []
        for product in root.findall('Product'):
            is_valid_product = False
            post_parent_id = get_child_text(product, 'post_parent')

            if post_parent_id: # Produktet er en variant
                parent_product = parents.get(post_parent_id)
                if parent_product:
                    if not get_child_text(product, 'post_content'):
                        set_child_text(product, 'post_content', get_child_text(parent_product, 'post_content'))
                    
                    # --- KORRIGERT LOGIKK FOR KATEGORI ---
                    # Finner kategorien fra morproduktet og kopierer den til varianten
                    parent_cat_element = parent_product.find('tax:product_cat', {'tax': 'tax'})
                    if parent_cat_element is not None and parent_cat_element.text:
                        variant_cat_element = product.find('tax:product_cat', {'tax': 'tax'})
                        if variant_cat_element is None:
                            variant_cat_element = ET.SubElement(product, '{tax}product_cat')
                        variant_cat_element.text = parent_cat_element.text
                    # --- SLUTT PÅ KORRIGERING ---
                        
                    set_child_text(product, 'post_parent', None)
                    set_child_text(product, 'parent_sku', None)
                    is_valid_product = True
            elif get_child_text(product, 'stock'): # Produktet er et enkeltprodukt
                is_valid_product = True
            
            if is_valid_product:
                sale_price_element = product.find('sale_price')
                if sale_price_element is not None:
                    product.remove(sale_price_element)
                final_products.append(product)
        
        logging.info(f"Prosessering ferdig. Total antall produkter i ny feed: {len(final_products)}")

        logging.info(f"Skriver den nye feeden til filen '{OUTPUT_FILE}'...")
        new_root = ET.Element("Products")
        new_root.extend(final_products)
        xml_string = ET.tostring(new_root, 'utf-8')
        reparsed = minidom.parseString(xml_string)
        pretty_xml_as_string = reparsed.toprettyxml(indent="  ", encoding="utf-8")
        with open(OUTPUT_FILE, "wb") as f:
            f.write(pretty_xml_as_string)
        
        logging.info(f"Filen '{OUTPUT_FILE}' ble opprettet successfully.")
        logging.info("--- Konverteringsjobb fullført: SUCCESS ---")

    except Exception as e:
        logging.error(f"En feil oppstod: {e}", exc_info=True)
        logging.error("--- Konverteringsjobb feilet: FAIL ---")
        sys.exit(1)

if __name__ == "__main__":
    setup_logging()
    run_conversion()
