
#import scrapy module
import scrapy

#time module is used to handle time-related tasks as to add some delay in process
#import time module
import time



#Lists are one of 4 built-in data types in Python used to store collections of data,
#the other 3 are Tuple, Set, and Dictionary, all with different qualities and usage
#declare an empty list named as url_list
url_list=[]



#create a spider
class streampoweredSpider(scrapy.Spider):

    name = "TopSellers"

    global game_href_list


    for x in range(0,10000,50):

        url_list.append('https://store.steampowered.com/search/?filter=topsellers&start='+str(x)+'&count=50')

    start_urls =url_list
    


    def parse(self, response):


        
        #XPath can be used to navigate through elements and attributes
        ##scrap href/url of each game
        game_hrefs=[]
        game_hrefs=response.xpath("//div[@id='search_resultsRows']/a/@href").extract()


        textfile = open("a_file.txt", "a+")
        


        for href in game_hrefs:
                # read the file after the appending:
                textfile.write(href + "\n")
        #close file
        textfile.close()
        #Python time method sleep() suspends execution for the given number of seconds
        time.sleep(1)       #suspens for 1 second
        
        
        
        
        
             
