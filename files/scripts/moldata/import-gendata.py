#!/usr/bin/env python
# coding=utf-8
# CSV files in csvs/ folder are expected to contain at least line name column
# Molecular characterization is then imported
import glob, csv
import MySQLdb
import ntpath

db = MySQLdb.connect(host="localhost", user="field2", passwd="smYEfYPpqa9FvJG4",db="field2")
cursor = db.cursor()
cursor.execute("BEGIN")

csvs_path = glob.glob("csvs/*.csv")

dirs = glob.glob("csvs/*")

for dire in dirs:
    csvs_path_dir = glob.glob(dire + "/*.csv")
    csvs_path = csvs_path + csvs_path_dir

year_col = ['ano','año','year']
origin_col = ['origen','orígen','origin','origem']
breeder_col = ['criadero','breeder']
line_col = ['linea','línea','variedad','line','line name','nombre','lineas']
trial_col = ['ensayo','trial']
status_col = ['status']

cont_lines = 0
ignored_lines = 0
for csv_path in csvs_path:
    rownum = 0
    csv_filename = ntpath.basename(csv_path)
    csv_file = open(csv_path, 'rb');
    csv_content = csv.reader(csv_file, delimiter=',', quotechar='"')
    line_name = origin = year = trial = breeder = None
    data = {}
    for row in csv_content:
        if rownum == 0:
            header = row
            rownum += 1
            continue
        else:
            colnum = 0
            for col in row:
                try:
                    if header[colnum].lower().strip() in year_col:
                        year = col
                    elif header[colnum].lower().strip() in origin_col:
                        origin = col
                    elif header[colnum].lower().strip() in trial_col:
                        trial = col
                    elif header[colnum].lower().strip() in line_col:
                        line_name = col
                    elif header[colnum].lower().strip() in breeder_col:
                        breeder = col
                    elif header[colnum].lower().strip() in status_col:
                        status = col
                    else:
                        data[header[colnum]] = col
                    colnum +=1
                except IndexError as e:
                    print str(e)
                    print "Error in ", csv_path, " col ", col, " colnum ", colnum
                    print row
        rownum += 1
        if not line_name: continue
        if origin == None:
            SQL = "SELECT id, origin, year FROM LineaCaracterizacion WHERE line_name = %s AND origin is NULL AND year = %s AND active"
            cursor.execute(SQL,[line_name, year])
        else:
            SQL = "SELECT id, origin, year FROM LineaCaracterizacion WHERE line_name = %s AND origin = %s AND year = %s AND active"
            cursor.execute(SQL,[line_name, origin, year])
        results = cursor.fetchone()
        if results:
            #print "Ignored ", line_name, " in ", csv_filename
            ignored_lines += 1
            continue
        SQL = "INSERT INTO LineaCaracterizacion (stamp, year, origin, line_name, breeder, filename, status) VALUES (NOW(), %s, %s, %s, %s, %s, %s)"
        cursor.execute(SQL,(year, origin, line_name, breeder,csv_filename, status))
        id_lineaCaracterizacion = cursor.lastrowid
        cont_lines += 1
        for k,v in data.iteritems():
            SQL = "INSERT INTO GenCaracterizacion (lineaCaracterizacion, name, value) VALUES (%s, %s, %s)"
            cursor.execute(SQL,(id_lineaCaracterizacion, k, v))
    print "Processed file:", csv_path
print "Total:", cont_lines, "new lines in", len(csvs_path), "files (", ignored_lines, "ignored )"

SQL = "UPDATE GenCaracterizacion SET name = 'lr34' WHERE name = 'lr 34'"
cursor.execute(SQL)
print "Executed ", SQL
SQL = "UPDATE GenCaracterizacion SET name = 'cnn/cs' WHERE name = 'cs/cnn'"
cursor.execute(SQL)
print "Executed ", SQL

cursor.execute("COMMIT")
db.close()
