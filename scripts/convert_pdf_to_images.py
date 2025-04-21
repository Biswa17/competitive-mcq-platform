import sys
from pdf2image import convert_from_path

pdf_path = sys.argv[1]
output_folder = sys.argv[2]
dpi = int(sys.argv[3]) if len(sys.argv) > 3 else 300

images = convert_from_path(pdf_path, dpi=dpi)
for i, img in enumerate(images):
    img.save(f"{output_folder}/page_{i+1}.jpg", "JPEG")
    print(f"Saved page {i+1}")
