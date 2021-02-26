# Web Development and Database Administration Project
Members:
* Montante, Jairus Miguel T.
* Lomeda, John Dominick
* Cancio, Eugene Kyle
* Concepcion, Neill Ira

-----
# Requirements
- Apache Server - [xampp](https://www.apachefriends.org/index.html) or [wamp](https://www.wampserver.com/en/)
- PHP - install with the Apache Server
- [Microsoft SQL Server 2012](https://www.microsoft.com/en-ph/download/details.aspx?id=35579) (and any SQL Editor that can handle this database engine like [SSMS](https://docs.microsoft.com/en-us/sql/ssms/download-sql-server-management-studio-ssms?view=sql-server-ver15), [Azure Data Studio](https://docs.microsoft.com/en-us/sql/azure-data-studio/download-azure-data-studio?view=sql-server-ver15), or [DBeaver](https://dbeaver.io))
    - You also need to download and install [SQLSRV Driver](https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-driver-api-reference?view=sql-server-ver15) and the [Microsoft ODBC Driver for SQL](https://docs.microsoft.com/en-us/sql/connect/odbc/microsoft-odbc-driver-for-sql-server?view=sql-server-ver15) in order to use SQL Server with PHP
    - Here is a [tutorial](https://youtu.be/upvALf8zJXg) if don't understand.
- An SMTP Server like [Mailtrap](https://www.mailtrap.io) or [hMailServer](https://www.hmailserver.com) (and an email viewer like MS Outlook or [Thunderbird](https://www.thunderbird.net/en-US/), if you are not using Mailtrap or any fake SMTP)
-----
# How to Reproduce
1. Extract the zip file in the htdocs folder of your webserver
2. Generate the sql script.
    - Open your SSMS or any other SQL Editor and copy the content of testdata.sql and execute it. It should create the database objects along with the data for testing.
3. Edit the .htaccess files
    - APP_BASE is the path where you extracted the files, from the localhost (htdocs)
    - DB_HOST is the server of your database
    - DB_DATABASE is the name of the database
    - DB_USER is your database username - BLANK (NULL) if using Windows Authentication
    - DB_PASS is your database password - BLANK (NULL) if using Windows Authentication
    - SMTP_HOST is the hostname of the SMTP server that you will use. I used a fake SMTP Server with [Mailtrap.io](https://www.mailtrap.io)
    - SMTP_USER is the username on your SMTP Server
    - SMTP_PASSWORD is the password on your SMTP Server
    - SMTP_PORT is the port used by your SMTP Server
4. Run your web server.
    - Either xampp or wamp.
5. The superuser credentials:
    - Email is admin1@gmail.com
    - Password is loonatheworld
    - The superuser is used for creating users and cannot add patients.
