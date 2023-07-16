from urllib.request import urlopen, Request
from urllib.error import URLError
from http.client import RemoteDisconnected
#install bs4
#python -m pip install bs4
from bs4 import BeautifulSoup
import datetime
import mysql.connector
import ssl

PARAM = {
    'host': "localhost",
    'user': "root",
    'password': "",
    'database': "plagiatdetect",
}


def scrapping_url(url):
    headers = {'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) '
                      'AppleWebKit/537.11 (KHTML, like Gecko) '
                      'Chrome/23.0.1271.64 Safari/537.11',
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Charset': 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
        'Accept-Encoding': 'none',
        'Accept-Language': 'fr-CH,fr;q=0.9',
        'Connection': 'keep-alive'}
    req = Request(url=url, headers=headers)
    try:
        page = urlopen(req, context=ssl._create_unverified_context())
        html_bytes = page.read()

        encodings = ['utf-8', 'ISO-8859-1']  # Add more encodings if needed
        for encoding in encodings:
            try:
                html = html_bytes.decode(encoding)
                soup = BeautifulSoup(html, "html.parser")
                text = soup.get_text()
                text = text.replace('\n', " ")
                text = text.replace('\t', " ")
                return text
            except UnicodeDecodeError:
                continue

        # If all encodings fail
        raise UnicodeDecodeError("Unable to decode HTML content using any encoding.")

    except (URLError, RemoteDisconnected) as e:
        # Gérer l'erreur de connexion distante
        pass
        """ print("Erreur de connexion distante :", e) """



def insertion_bd(link, contenu):
    request="""insert into documents (links, contenu, created_at)
                    values (%s, %s, %s)"""
    params=(link, contenu, datetime.date.today())
    with mysql.connector.connect(**PARAM) as db :
        with db.cursor() as c:
            c.execute(request, params)
            db.commit()

def selection_bd(link):
    request = "SELECT * FROM documents WHERE links = %s"
    param = (link,)

    with mysql.connector.connect(**PARAM) as db:
        with db.cursor() as c:
            c.execute(request, param)
            result = c.fetchone()
            if result is None:
                return True
        return False
i=0
# Ouvrir le fichier en mode lecture
""" with open('D:/laragon/www/PlagiatDetect/public/google_links.txt', 'r') as fichier:
    # Lire le fichier ligne par ligne
    for ligne in fichier:
        # Traiter chaque ligne
        if(selection_bd(ligne)==True) :
            contenu=scrapping_url(ligne)
            if contenu is not None:
                insertion_bd(ligne, contenu) """
url="https://fr.wikipedia.org/wiki/Base_de_donn%C3%A9es"
print(scrapping_url(url))
print("Executionsss avec success")



# Fermer le fichier automatiquement à la fin du bloc `with`



""" def scrapping_url(url):
    headers = {'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) '
                      'AppleWebKit/537.11 (KHTML, like Gecko) '
                      'Chrome/23.0.1271.64 Safari/537.11',
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Charset': 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
        'Accept-Encoding': 'none',
        'Accept-Language': 'fr-CH,fr;q=0.9',
        'Connection': 'keep-alive'}
    req=Request(url=url, headers=headers)
    try:
        page = urlopen(req)
        html_bytes = page.read()
        html = html_bytes.decode("utf-8")
        soup = BeautifulSoup(html, "html.parser")
        so=soup.get_text()
        so=so.replace('\n', " ")
        text=so.replace('\t', " ")
        return text
    except URLError as e:
        pass """
