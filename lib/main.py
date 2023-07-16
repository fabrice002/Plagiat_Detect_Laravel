import sys
import os
from PyPDF2 import PdfReader
from docx import Document
import chardet

def extract_text_from_pdf(pdf_path):
    text = ""
    with open(pdf_path, "rb") as file:
        reader = PdfReader(file)
        for page in reader.pages:
            text += page.extract_text()
    return text

def extract_text_from_word(docx_path):
    text = ""
    doc = Document(docx_path)
    for para in doc.paragraphs:
        text += para.text
    return text

def extract_text_from_txt(txt_path):
    with open(txt_path, "rb") as file:
        raw_data = file.read()
        detected_encoding = chardet.detect(raw_data)["encoding"]
    with open(txt_path, "r", encoding=detected_encoding, errors='ignore') as file:
        text = file.read()
    return text

param1 = sys.argv[1]
param2 = sys.argv[2]

if param2 == "pdf":
    text = extract_text_from_pdf(param1)
elif param2 == "word":
    text = extract_text_from_word(param1)
elif param2 == "txt":
    text = extract_text_from_txt(param1)

# Replace newline characters with double spaces
text = text.replace('\n', ' ')

temp_file = "extracted_text.txt"
with open(temp_file, "w", encoding="utf-8") as file:
        file.write(text)
print(0)
""" try:
    print(text)
except UnicodeEncodeError:
    temp_file = "extracted_text.txt"
    with open(temp_file, "w", encoding="utf-8") as file:
        file.write(text)
    print(f"Unable to print text. Extracted text is saved to: {os.path.abspath(temp_file)}") """
