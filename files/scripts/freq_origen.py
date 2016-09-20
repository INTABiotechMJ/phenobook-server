#!/usr/bin/env python
# coding=utf-8
# CSV files in csvs/ folder are expected to contain at least line name column
# Molecular characterization is then imported
import glob, csv
import MySQLdb
import ntpath

db = MySQLdb.connect(host="localhost", user="root", passwd="manolin",db="phenobook")
cursor = db.cursor()
SQL = "SELECT id, year FROM LineaCaracterizacion WHERE active AND year IS NOT NULL"
cursor.execute(SQL)
resultsLine = cursor.fetchall()
resSi = {}
resNo = {}
for rowLine in resultsLine:
    ano = rowLine[1]
    SQL = "SELECT value FROM GenCaracterizacion WHERE name = '1BL/1RS' AND active AND lineaCaracterizacion = %s"
    cursor.execute(SQL,[rowLine[0]])
    resultsGen = cursor.fetchall()
    for rowGen in resultsGen:
        if rowGen[0].lower() == "si":
            if ano in resSi:
                resSi[ano] += 1
            else:
                resSi[ano] = 1
        if rowGen[0].lower() == "no":
            if ano in resNo:
                resNo[ano] += 1
            else:
                resNo[ano] = 1
print "si", resSi
print "no", resNo
db.close()
