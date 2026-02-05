# Local Development Guide

This guide walks you through setting up a local development environment for the Azure PHP CI/CD Portal project.

## Prerequisites

Before you begin, ensure you have:
- A computer running Windows, macOS, or Linux
- Administrator/sudo access for installing software
- A text editor or IDE (VS Code, PhpStorm, Sublime Text, etc.)
- Git installed (for version control)

## Step 1: Install PHP 8.0+

The portal requires PHP 8.0 or higher. Follow the instructions for your operating system:

### Windows

**Option 1: Using XAMPP (Recommended for Beginners)**

1. Download XAMPP from: https://www.apachefriends.org/
2. Run the installer and select at least:
   - Apache
   - PHP (ensure version 8.0+)
3. Install to the default location (e.g., `C:\xampp`)
4. Add PHP to your PATH:
   - Open System Properties → Environment Variables
   - Edit the `Path` variable
   - Add: `C:\xampp\php`
5. Verify installation:
   ```cmd
   php -v
   ```

**Option 2: Using Chocolatey**

If you have Chocolatey installed:
```powershell
choco install php --version=8.0
```

**Option 3: Manual Installation**

1. Download PHP from: https://windows.php.net/download/
2. Extract to `C:\php`
3. Add `C:\php` to your PATH
4. Copy `php.ini-development` to `php.ini`
5. Verify installation:
   ```cmd
   php -v
   ```

### macOS

**Option 1: Using Homebrew (Recommended)**

1. Install Homebrew if not already installed:
   ```bash
   /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
   ```

2. Install PHP:
   ```bash
   brew install php@8.0
   ```

3. Link PHP (if needed):
   ```bash
   brew link php@8.0
   ```

4. Verify installation:
   ```bash
   php -v
   ```

**Option 2: Using MacPorts**

```bash
sudo port install php80
sudo port select --set php php80
```

### Linux

**Ubuntu/Debian:**

```bash
# Add PHP repository
sudo apt update
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update

# Install PHP 8.0
sudo apt install php8.0 php8.0-cli php8.0-common php8.0-mbstring php8.0-xml

# Verify installation
php -v
```

**CentOS/RHEL/Fedora:**

```bash
# Enable EPEL and Remi repositories
sudo dnf install epel-release
sudo dnf install https://rpms.remirepo.net/fedora/remi-release-$(rpm -E %fedora).rpm

# Install PHP 8.0
sudo dnf module reset php
sudo dnf module install php:remi-8.0

# Verify installation
php -v
```

**Arch Linux:**

```bash
sudo pacman -S php

# Verify installation
php -v
```

### Verify PHP Installation

After installation, verify PHP is working:

```bash
php -v
```

You should see output like:
```
PHP 8.0.x (cli) (built: ...)
Copyright (c) The PHP Group
Zend Engine v4.0.x, Copyright (c) Zend Technologies
```

## Step 2: Install Composer

Composer is PHP's dependency manager, used for installing PHPUnit and other dependencies.

### Windows

**Option 1: Using the Installer (Recommended)**

1. Download Composer-Setup.exe from: https://getcomposer.org/download/
2. Run the installer
3. Follow the prompts (it will detect your PHP installation)
4. Verify installation:
   ```cmd
   composer --version
   ```

**Option 2: Manual Installation**

```cmd
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
move composer.phar C:\bin\composer.phar
```

Create a batch file `C:\bin\composer.bat`:
```batch
@php "%~dp0composer.phar" %*
```

Add `C:\bin` to your PATH.

### macOS/Linux

**Option 1: Using the Install Script (Recommended)**

```bash
# Download and run installer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# Move to global location
sudo mv composer.phar /usr/local/bin/composer

# Make executable
sudo chmod +x /usr/local/bin/composer
```

**Option 2: Using Homebrew (macOS only)**

```bash
brew install composer
```

### Verify Composer Installation

```bash
composer --version
```

You should see output like:
```
Composer version 2.x.x
```

## Step 3: Clone the Repository

Clone the project repository to your local machine:

```bash
# Using HTTPS
git clone https://github.com/your-username/azure-php-cicd-portal.git

# Or using SSH (if you have SSH keys set up)
git clone git@github.com:your-username/azure-php-cicd-portal.git

# Navigate to the project directory
cd azure-php-cicd-portal
```

## Step 4: Install Dependencies

Install PHP dependencies using Composer:

```bash
composer install
```

This will:
- Read the `composer.json` file
- Download PHPUnit and other dependencies
- Create a `vendor/` directory with all dependencies
- Generate the `composer.lock` file (if not present)

### Expected Output

You should see output like:
```
Loading composer repositories with package information
Installing dependencies from lock file
Package operations: X installs, 0 updates, 0 removals
  - Installing phpunit/phpunit (x.x.x): Downloading (100%)
...
Generating autoload files
```

## Step 5: Configure the Application

The application uses configuration constants defined in `includes/config.php`. You may want to customize these for local development:

1. Open `includes/config.php` in your editor

2. Review and modify constants as needed:
   ```php
   define('SITE_NAME', 'Post Graduate Course in Cloud Computing');
   define('SITE_URL', 'http://localhost:8000'); // Update for local dev
   define('CONTACT_EMAIL', 'info@example.com');
   ```

3. Save the file

**Note**: Don't commit local configuration changes to Git. Consider using environment variables for sensitive configuration.

## Step 6: Run the Local Development Server

PHP includes a built-in web server perfect for local development:

```bash
php -S localhost:8000 -t public
```

### Understanding the Command

- `php -S`: Start PHP's built-in web server
- `localhost:8000`: Listen on localhost, port 8000
- `-t public`: Set the document root to the `public/` directory

### Expected Output

You should see:
```
[Thu Jan 1 12:00:00 2024] PHP 8.0.x Development Server (http://localhost:8000) started
```

### Access the Application

Open your web browser and navigate to:
- **Home**: http://localhost:8000/
- **Curriculum**: http://localhost:8000/curriculum.php
- **Faculty**: http://localhost:8000/faculty.php
- **Admissions**: http://localhost:8000/admissions.php
- **Contact**: http://localhost:8000/contact.php

### Stopping the Server

Press `Ctrl+C` in the terminal to stop the server.

### Using a Different Port

If port 8000 is already in use:

```bash
php -S localhost:3000 -t public
```

Then access the application at http://localhost:3000/

## Step 7: Run Tests Locally

The project includes PHPUnit tests for validating functionality.

### Run All Tests

```bash
vendor/bin/phpunit
```

### Run Specific Test Suites

```bash
# Run only unit tests
vendor/bin/phpunit tests/unit

# Run a specific test file
vendor/bin/phpunit tests/unit/PageRenderTest.php

# Run a specific test method
vendor/bin/phpunit --filter testHomePageLoadsWithoutErrors
```

### Understanding Test Output

Successful test run:
```
PHPUnit 9.x.x by Sebastian Bergmann and contributors.

.....                                                               5 / 5 (100%)

Time: 00:00.123, Memory: 10.00 MB

OK (5 tests, 10 assertions)
```

Failed test run:
```
PHPUnit 9.x.x by Sebastian Bergmann and contributors.

..F..                                                               5 / 5 (100%)

Time: 00:00.123, Memory: 10.00 MB

There was 1 failure:

1) PageRenderTest::testHomePageLoadsWithoutErrors
Failed asserting that...
```

### Test Coverage (Optional)

Generate code coverage report (requires Xdebug):

```bash
vendor/bin/phpunit --coverage-html coverage
```

Open `coverage/index.html` in your browser to view the report.

## Step 8: Development Workflow

### Making Changes

1. **Create a feature branch:**
   ```bash
   git checkout -b feature/my-new-feature
   ```

2. **Make your changes** in your editor

3. **Test locally:**
   - Refresh the browser to see changes
   - Run tests: `vendor/bin/phpunit`

4. **Commit your changes:**
   ```bash
   git add .
   git commit -m "Add new feature"
   ```

5. **Push to GitHub:**
   ```bash
   git push origin feature/my-new-feature
   ```

6. **Create a pull request** on GitHub

### Live Reloading (Optional)

For automatic browser refresh on file changes, you can use tools like:

**Browser-sync:**
```bash
npm install -g browser-sync
browser-sync start --proxy "localhost:8000" --files "public/**/*.php, includes/**/*.php, public/assets/**/*"
```

**PHP-Watcher:**
```bash
composer require seregazhuk/php-watcher --dev
vendor/bin/php-watcher php -S localhost:8000 -t public
```

## Step 9: Debugging

### Enable Error Display

For local development, enable error display in PHP:

1. Create or edit `public/.htaccess` (if using Apache)
2. Or add to the top of `public/index.php`:
   ```php
   <?php
   // Development error settings
   ini_set('display_errors', '1');
   ini_set('display_startup_errors', '1');
   error_reporting(E_ALL);
   ```

**Important**: Never enable error display in production!

### Using Xdebug

Xdebug is a powerful debugging tool for PHP:

**Install Xdebug:**

```bash
# Ubuntu/Debian
sudo apt install php8.0-xdebug

# macOS (Homebrew)
pecl install xdebug

# Windows (XAMPP)
# Xdebug is usually included, just enable it in php.ini
```

**Configure Xdebug** in `php.ini`:

```ini
[xdebug]
zend_extension=xdebug.so
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_port=9003
```

**Use with VS Code:**

1. Install the "PHP Debug" extension
2. Create `.vscode/launch.json`:
   ```json
   {
     "version": "0.2.0",
     "configurations": [
       {
         "name": "Listen for Xdebug",
         "type": "php",
         "request": "launch",
         "port": 9003
       }
     ]
   }
   ```
3. Set breakpoints and start debugging (F5)

### Viewing Logs

PHP errors are logged to:
- **Windows (XAMPP)**: `C:\xampp\php\logs\php_error_log`
- **macOS (Homebrew)**: `/usr/local/var/log/php-error.log`
- **Linux**: `/var/log/php/error.log` or check `php.ini` for `error_log` setting

View logs in real-time:

```bash
# macOS/Linux
tail -f /path/to/php_error.log

# Windows (PowerShell)
Get-Content C:\xampp\php\logs\php_error_log -Wait
```

## Step 10: Code Quality Tools (Optional)

### PHP CodeSniffer

Check code style and standards:

```bash
# Install
composer require --dev squizlabs/php_codesniffer

# Run
vendor/bin/phpcs --standard=PSR12 public includes
```

### PHP-CS-Fixer

Automatically fix code style issues:

```bash
# Install
composer require --dev friendsofphp/php-cs-fixer

# Run
vendor/bin/php-cs-fixer fix public
vendor/bin/php-cs-fixer fix includes
```

### PHPStan

Static analysis for finding bugs:

```bash
# Install
composer require --dev phpstan/phpstan

# Run
vendor/bin/phpstan analyse public includes
```

## Troubleshooting

### Issue: "php: command not found"

**Solution**: PHP is not in your PATH.
- Windows: Add PHP directory to PATH in System Environment Variables
- macOS/Linux: Add to `~/.bashrc` or `~/.zshrc`:
  ```bash
  export PATH="/path/to/php:$PATH"
  ```

### Issue: "composer: command not found"

**Solution**: Composer is not installed or not in PATH.
- Reinstall Composer following Step 2
- Verify installation: `composer --version`

### Issue: Port 8000 already in use

**Solution**: Use a different port:
```bash
php -S localhost:3000 -t public
```

Or find and kill the process using port 8000:
```bash
# macOS/Linux
lsof -ti:8000 | xargs kill

# Windows
netstat -ano | findstr :8000
taskkill /PID <process-id> /F
```

### Issue: "Class not found" errors

**Solution**: Regenerate Composer autoload:
```bash
composer dump-autoload
```

### Issue: Permission denied errors (Linux/macOS)

**Solution**: Fix file permissions:
```bash
chmod -R 755 public
chmod -R 755 includes
```

### Issue: Changes not reflecting in browser

**Solution**:
- Hard refresh: `Ctrl+Shift+R` (Windows/Linux) or `Cmd+Shift+R` (macOS)
- Clear browser cache
- Restart PHP development server
- Check if you're editing the correct file

### Issue: Tests failing locally but passing in CI

**Solution**:
- Check PHP version matches CI (8.0+)
- Ensure all dependencies are installed: `composer install`
- Check for environment-specific issues
- Review test output for specific errors

## IDE Setup

### Visual Studio Code

Recommended extensions:
- PHP Intelephense
- PHP Debug
- PHP DocBlocker
- EditorConfig for VS Code

**Settings** (`.vscode/settings.json`):
```json
{
  "php.validate.executablePath": "/path/to/php",
  "php.suggest.basic": false,
  "intelephense.files.maxSize": 1000000
}
```

### PhpStorm

PhpStorm has built-in PHP support. Configure:
1. Settings → PHP → CLI Interpreter
2. Select your PHP installation
3. Enable Composer support
4. Configure Xdebug

### Sublime Text

Install packages:
- PHP Companion
- PHPUnit Completions
- SublimeLinter-php

## Next Steps

After setting up your local environment:

1. **Explore the codebase**: Familiarize yourself with the project structure
2. **Make a test change**: Edit a page and see it update locally
3. **Run tests**: Ensure everything works: `vendor/bin/phpunit`
4. **Read other documentation**:
   - [Azure Setup Guide](./azure-setup.md)
   - [GitHub Setup Guide](./github-setup.md)
   - [Deployment Guide](./deployment-guide.md)

## Additional Resources

- [PHP Documentation](https://www.php.net/docs.php)
- [Composer Documentation](https://getcomposer.org/doc/)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [PHP The Right Way](https://phptherightway.com/)
- [PHP-FIG Standards](https://www.php-fig.org/psr/)

## Tips for Productive Development

1. **Use version control**: Commit often with meaningful messages
2. **Write tests**: Add tests for new features
3. **Follow coding standards**: Use PSR-12 for consistency
4. **Document your code**: Add comments for complex logic
5. **Keep dependencies updated**: Run `composer update` regularly
6. **Use a debugger**: Don't rely solely on `var_dump()`
7. **Read error messages**: They usually tell you exactly what's wrong
8. **Ask for help**: Use GitHub issues or discussions for questions
