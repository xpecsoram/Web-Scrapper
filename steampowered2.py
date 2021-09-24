# open a_file.txt file to read data
# get all url from a_file.txt as content
with open("a_file.txt") as f:
    #read line by line and store in content
    content = f.readlines()

#create content as a list for start_urls
#start_urls contain those links from which the spider start crawling
content = [x.strip() for x in content]

#remove duplication from list
content = list(dict.fromkeys(content))




##Scrapy is an open source and collaborative framework
#for extracting the data you need from websites
#import scrapy module
import scrapy

#time module is used to handle time-related tasks as to add some delay in process
#import time module
import time

#django library is used to remove html tags as <b> from any string 
#import strip_tags from django module
from django.utils.html import strip_tags
#re module is used to remove special characters as \t\r from string
import re



# Python can be used in database applications.
#One of the most popular databases is MySQL.
#import mysql connector driver for mysql connection and also to used mysql for inserting data
import mysql.connector


#Start by creating a connection to the database.
#build connection with database
mydb = mysql.connector.connect(
  user="root",
  password="",
  database="streampowered",
  host="localhost"
)

#create database cursor to execute data queries
mycursor = mydb.cursor()


#Spiders are classes that you define and that Scrapy uses to scrape information from a website (or a group of websites
class streampoweredSpider(scrapy.Spider):

    #identifies the Spider. It must be unique within a project,
    #that is, you can’t set the same name for different Spiders
    name = "TopSellers"
    
    #start_urls contain those links from which the spider start crawling
    start_urls =content
    

    def parse(self, response):
       

        #scraping data of each game under the div having class details_block 
        game_data=response.xpath("//div[@class='details_block']").extract()

        #removing html tags as <b> from scrapted string named as game_data
        game_data=strip_tags(game_data)

        
        
        #built variables for database
        #title is used to store the title of game
        #split function is using to split all value into array
        #[0] is used to store first index value after spliting
        #replace function is used to replace string with another string
        if(game_data.find('Title')!= -1):
            title1=game_data.split("Title:")
            title2=title1[1].split("Genre:")
            title=title2[0].replace('\\n', '').replace('\\r', '').replace('\\t', '')
        else:
            title=''


        if(game_data.find('Genre')!= -1):
            Genre1=game_data.split("Genre:")
            Genre2=Genre1[1].split("Developer:")
            Genre=Genre2[0].replace('\\n', '').replace('\\r', '').replace('\\t', '')
        else:
            Genre=''


            
        




        if(game_data.find('Developer')!= -1):
            Developer1=game_data.split("Developer:")
            Developer2=Developer1[1].split("Publisher:")
            Developer=Developer2[0].replace('\\n', '').replace('\\r', '').replace('\\t', '')
        else:
            Developer=''


        if(game_data.find('Publisher')!= -1):
            Publisher1=game_data.split("Publisher:")
            if(Publisher1[1].find('Franchise')!= -1):
                Publisher2=Publisher1[1].split("Franchise:")
            elif(Publisher1[1].find('Release Date')!= -1):
                Publisher2=Publisher1[1].split("Release Date")
            else:
                Publisher2=Publisher1[1][0:20]
            Publisher=Publisher2[0].replace('\\n', '').replace('\\r', '').replace('\\t', '')
        else:
            Publisher=''
            

        if(game_data.find('Release Date')!= -1):
            Release_Date1=game_data.split("Release Date:")
            Release_Date=Release_Date1[1][0:13]
            
        else:
            Release_Date=''
        Release_Date=Release_Date.replace("\\", "")


            
        if(game_data.find('Franchise')!= -1):
            Franchise1=game_data.split("Franchise:")
            if(Franchise1[1].find('Release Date')!= -1):
                Franchise2=Franchise1[1].split("Release Date")
            elif(Franchise1[1].find('Languages')!= -1):
                Franchise2=Franchise1[1].split("Languages")
            else:
                Franchise2=Franchise1[1][0:30]
                
            Franchise=Franchise2[0].replace('\\n', '').replace('\\r', '').replace('\\t', '')
            
            
        else:
            Franchise=''
        


            
        if(game_data.find('Languages')!= -1):
            Languages1=game_data.split("Languages")
            Languages2=Languages1[1].split("Listed languages")
            Languages=Languages2[0].replace('\\n', '').replace('\\r', '').replace('\\t', '').replace('\'', '')
            Languages=Languages.replace(':','').replace(']','')
        else:
            Languages=''
        
        
       
        #scraping game_area_description from a div having id=game_area_description
        #game_area_description=response.xpath("//div[@id='game_area_description']/p/text()").extract()

        
        #scraping discount_pct from a div having class=discount_pct
        discount_pct=response.xpath("//div[@class='discount_pct']/text()").extract()
        #scraping discount_original_price from a div having class=discount_bloc
        discount_original_price=response.xpath("//div[@class='discount_original_price']/text()").extract()

        #scraping discount_final_price from a div having class=discount_final_price
        discount_final_price=response.xpath("//div[@class='discount_final_price']/text()").extract()


        #end for built variables for database
        ####################################

        if(len(discount_pct)>=1):
            discount_pct=discount_pct[0]
        else:
            discount_pct=''
        

        if(len(discount_original_price)>=1):
            discount_original_price=discount_original_price[0]
        else:
            discount_original_price=''
            

        if(len(discount_final_price)>=1):
                discount_final_price=discount_final_price[0]
        else:
            discount_final_price=''


        

        #insert data into mysql using 'insert into' query
        if(title!='' and Genre!='' and discount_pct!=''):
            discount_pct=discount_pct.replace('-','').replace('%','')
            sql = "INSERT INTO streampowered SET `title`='"+title+"',`action`='"+Genre+"',`Developer`='"+Developer+"',`Publisher`='"+Publisher+"',`Franchise`='"+Franchise+"',`Release_Date`='"+Release_Date+"' ,`Languages`='"+Languages+"',`discount_pct`='"+str(discount_pct)+"',`discount_original_price`='"+str(discount_original_price)+"',`discount_final_price`='"+str(discount_final_price)+"'"
            print(sql)
            #execute query  using mycursor
            mycursor.execute(sql)
            mydb.commit()
        
        #time is a module
        #under time module sleep function is used to add some delay in processing
        time.sleep(1)
        
