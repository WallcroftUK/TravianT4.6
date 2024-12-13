# Guide to Install Nginx, MySQL 8.0, and PHP 7.3 on CentOS 7

This guide will walk you through the process of setting up **Nginx**, **MySQL 8.0**, and **PHP 7.3** with the required extensions on a CentOS 7 server.

## Step 1: Update your system
First, ensure your system is up-to-date by running:

```bash
sudo yum update -y
```

## Step 2: Install EPEL and REMI Repositories

To install PHP 7.3 and other required packages, we need to enable the **EPEL** and **REMI** repositories.

```bash
# Install EPEL (Extra Packages for Enterprise Linux) repository
sudo yum install -y epel-release

# Install REMI repository for PHP packages
sudo yum install -y https://rpms.remirepo.net/enterprise/remi-release-7.rpm
```

## Step 3: Enable PHP 7.3 from REMI Repository

Now, enable the **PHP 7.3** module from the REMI repository:

```bash
# Enable PHP 7.3 from REMI repository
sudo yum-config-manager --enable remi-php73
```

## Step 4: Install PHP 7.3 and Extensions

Now, install PHP 7.3 along with the required extensions for Nginx:

```bash
# Install PHP 7.3 and the necessary extensions
sudo yum install -y php73 php73-mysqlnd php73-mbstring php73-xml php73-curl php73-zip php73-gd php73-devel php73-redis
```

## Step 5: Install Nginx

To install **Nginx** on CentOS 7, first install the EPEL repository and then Nginx:

```bash
# Install Nginx
sudo yum install -y nginx
```

## Step 6: Install MySQL 8.0

To install **MySQL 8.0**:

```bash
# Install MySQL 8.0 Community repository
sudo yum install -y https://dev.mysql.com/get/mysql84-community-release-el7-1.noarch.rpm

sudo curl -o /etc/pki/rpm-gpg/RPM-GPG-KEY-mysql-2023 https://repo.mysql.com/RPM-GPG-KEY-mysql-2023

sudo rpm --import /etc/pki/rpm-gpg/RPM-GPG-KEY-mysql-2023

# Install MySQL Server
sudo yum install -y mysql-community-server
```

## Step 7: Start and Enable Services

Start and enable **MySQL**, **Nginx**, and **PHP-FPM** to start on boot:

```bash
# Start MySQL service
sudo systemctl start mysqld
sudo systemctl enable mysqld

# Start Nginx service
sudo systemctl start nginx
sudo systemctl enable nginx

# Start PHP-FPM service
sudo systemctl start php-fpm
sudo systemctl enable php-fpm
```

## Step 8: Configure Nginx to Use PHP

To make Nginx work with PHP, update the Nginx configuration:

1. Edit the Nginx configuration file (`/etc/nginx/nginx.conf` or `/etc/nginx/conf.d/default.conf`) to include the PHP configuration.

```bash
sudo nano /etc/nginx/conf.d/default.conf
```

2. Add the following inside the server block:

```nginx
server {
    listen       80;
    server_name  localhost;

    root   /usr/share/nginx/html;
    index  index.php index.html index.htm;

    location / {
        try_files $uri $uri/ =404;
    }

    # PHP-FPM configuration
    location ~ \.php$ {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME /usr/share/nginx/html$document_root$fastcgi_script_name;
        include        fastcgi_params;
    }
}
```

3. Test Nginx configuration and reload:

```bash
# Test Nginx configuration
sudo nginx -t

# Reload Nginx to apply changes
sudo systemctl reload nginx
```

## Step 9: Secure MySQL Installation

Run the MySQL secure installation process:

```bash
sudo mysql_secure_installation
```

Enter the temporary password found in the MySQL log, and follow the prompts to secure the MySQL installation.

## Step 10: Verify Installation

1. Verify PHP installation by creating a `info.php` file in `/usr/share/nginx/html/`:

```bash
sudo nano /usr/share/nginx/html/info.php
```

Add the following content:

```php
<?php
phpinfo();
?>
```

2. Open your browser and navigate to `http://<your-server-ip>/info.php`. If everything is working correctly, you should see the PHP information page.

3. To check Nginx status:

```bash
sudo systemctl status nginx
```

4. To check PHP-FPM status:

```bash
sudo systemctl status php-fpm
```

5. To check MySQL status:

```bash
sudo systemctl status mysqld
```


/travian/molonlave/servers/dev/include/connection.php