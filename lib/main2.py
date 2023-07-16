#installer nltk
import nltk
from nltk.data import load
#installer Google
#python -m pip install google-search-results
from serpapi import GoogleSearch
#python -m pip install urllib3
from urllib.request import urlopen, Request
from urllib.error import URLError
#install bs4
#python -m pip install bs4
from bs4 import BeautifulSoup
#Pour Mysql
#python -m pip install mysql-connector-python
import mysql.connector
import datetime
import random

PARAM = {
    'host': "localhost",
    'user': "root",
    'password': "",
    'database': "plagiatdetect",
}


#Lecture du fichier
def file_read(document_path):
    f=open(document_path,"r")
    orig=f.read().replace("\n"," ")
    f.close()
    return orig
#Transformation  de text en phrase.
def sent_tokenizes(text, language="french"):
    print(f"tokenizers/punkt/{language}.pickle")
    tokenizer = load(f"tokenizers/punkt/{language}.pickle")
    return tokenizer.tokenize(text)
#Appelle API google scholar
def api_google(sentence):
    params = {
        "engine": "google",
        "q": sentence,
        "api_key": "5ffd4edbb177e49d901b80437009e5bf6d659c5e040b10e3eebe8f607dc727b3"
    }
    search = GoogleSearch(params)
    results = search.get_dict()
    organic_results = results["organic_results"]
    link=[i['link'] for i in organic_results]
    return link
#Scrapping des different url
def scrapping_url(url):
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
        pass
def selection_bd(link):
    request = "select * from documents where links=%s"
    param=(link,)

    with mysql.connector.connect(**PARAM) as db :
        with db.cursor() as c:
            c.execute(request, param)
            resultats = c.fetchmany(2)
            if not resultats:
                return True
        return False
def insertion_bd(link, contenu):
    request="""insert into documents (links, contenu, created_at)
                    values (%s, %s, %s)"""
    params=(link, contenu, datetime.date.today())
    with mysql.connector.connect(**PARAM) as db :
        with db.cursor() as c:
            c.execute(request, params)
            db.commit()

def main(document_path):
    print(document_path)
    pass
    """ orig=file_read(document_path)
    #print(orig)
    token_sent=sent_tokenizes(orig)
    rand=random.randint(2, len(token_sent)//2)
    random.shuffle(token_sent)
    link=api_google(token_sent[0])
    for url in link:
        contenu=scrapping_url(url)
        if contenu is not None:
            if selection_bd(url) ==True:
                insertion_bd(url, contenu)
                print('ok')
    for i in range(rand):
        link=api_google(token_sent[i])
        for url in link:
            contenu=scrapping_url(url)
            if contenu is not None:
                if selection_bd(url) ==True:
                    insertion_bd(url, contenu)
                    print('ok')
    return True """

#Main
""" link=api_google('base de donnée')
#print(scrapping_url('https://base-donnees-publique.medicaments.gouv.fr/'))

for url in link:
    contenu=scrapping_url(url)
    if contenu is not None:
        if selection_bd(url) ==True:
            insertion_bd(url, contenu)
            print('ok') """

s=input()
main(s)
