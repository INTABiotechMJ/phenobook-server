#install.packages("RMySQL")
library(RMySQL)
con <- dbConnect(MySQL(),user="root", password="manolin",dbname="phenobook", host="localhost")