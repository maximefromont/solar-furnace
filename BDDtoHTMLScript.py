import pymysql
import time

# Database connection details
servername = "localhost"
username = "root"
password = "e"
dbname = "fsp"

# Sleep interval between iterations (in seconds)
sleep_interval = 1

while True:
    conn = pymysql.connect(host=servername, user=username, password=password, db=dbname)

    cursor = conn.cursor()

    # Query to get the latest 'target_temp' value from the 'target' table
    query_target_temp = "SELECT target_temp FROM target ORDER BY id_target DESC LIMIT 1"

    cursor.execute(query_target_temp)
    target_temp = int(cursor.fetchone()[0])

    # Query to get the latest 'water_temp' value from the 'data' table
    query_water_temp = "SELECT water_temp FROM Data ORDER BY id_data DESC LIMIT 1"

    cursor.execute(query_water_temp)
    water_temp = int(cursor.fetchone()[0])

    conn.close()

    # Write the values to 'index.html'
    with open("/var/www/html/index.html", "w") as file:
        file.write(f"<!doctype html>\n\n<title>;{water_temp};{target_temp}\n")

    print(f"Values written to 'index.html': water_temp={water_temp}, target_temp={target_temp}")

    # Sleep before the next iteration to prevent overloading the raspberry pi
    time.sleep(sleep_interval)