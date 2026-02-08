# Smart Report Engine – PHP MySQL Report Generator & Reports Builder (Community Edition)

![License](https://img.shields.io/badge/license-community-blue)
![PHP](https://img.shields.io/badge/php-7.4%2B-blue)
![MySQL](https://img.shields.io/badge/mysql-mariadb-supported-orange)
![Status](https://img.shields.io/badge/status-active-brightgreen)

Smart Report Engine is a **PHP reporting library for MySQL and MariaDB** that allows developers to generate professional, dynamic reports programmatically inside their own PHP applications.  
Build SQL reports, grouped summaries, calculated fields, and formatted outputs using a clean PHP API instead of manually writing report logic.

[![Watch the video](https://mysqlreports.com/video.png)](https://www.youtube.com/embed/ZLa24Eo5gmE)

---

## 🔹 Official Project

Official Website: https://www.mysqlreports.com/  
Product Page: https://mysqlreports.com/mysql-reporting-tools/smart-report-engine/  
Community Edition Repository: https://github.com/webuccinoco/PHP-Reporting-Library-For-MYSQL  

⚠️ This is the original and official repository of Smart Report Engine Community Edition.

---

## 📑 Table of Contents

- [Why Smart Report Engine is a powerful PHP reporting library for MySQL](#why-smart-report-engine-is-a-powerful-php-reporting-library-for-mysql)
- [Features](#-features)
- [Community Edition vs Pro Edition vs Smart Report Maker](#comunity-edition-vs-pro-edition-vs-smart-report-maker)
- [Screenshots & Videos](#-screenshots--videos)
- [Use Cases](#-use-cases)
- [Getting started](#getting-started-with-smart-report-engine---php-reports-builder-for-mysql---community-edition)
- [Code Example & Walkthrough](#code-example--walkthrough)
- [Documentation](#--documentation)
- [Frequently Asked Questions (FAQ)](#frequently-asked-questions-faq)
- [Customer Support](#customer-support)
- [Important links](#important-links)
- [Support the Project](#-support-the-project)
- [License](#-license)
- [Trademark](#-trademark)
- [Contributing](#-contributing)
- [Topics](#-topics)
- [Author](#-author)

---

## Why Smart Report Engine is a powerful PHP Reporting Library for MySQL

### Streamlined Development & Integration

- Intuitive API: Effortlessly generate professional PHP reports for MySQL or MariaDB in minutes using a developer-friendly interface.
- Flexible Architecture: As a robust PHP library, it offers extensive methods that are easy to manage, customize, and extend directly within your codebase.
- Native PHP & Laravel Ready: Every license includes both the native PHP engine and a dedicated Laravel package for seamless integration.
- Rapid Deployment: Drastically cut down on development, debugging, and maintenance hours by automating the creation of fully functional reports.

### Advanced Reporting Capabilities

- Versatile Data Sources: Build unlimited reports using single or multiple tables, views, or complex SQL queries.
- Multi-Level Grouping: Create high-density insights by grouping data across multiple layers, such as region, country, and city.
- Dynamic Calculations: Add calculated "virtual" columns and include automated subtotals or grand totals (Sum, Avg, Min, Max, Count) for any data group.
- Smart Formatting: Use conditional formatting to highlight critical data (e.g., flagging low stock in red) or apply custom cell styling like country flags and star ratings.

### Powerful Interactivity & Security

- Dynamic Filtering: Create parameterized reports—such as date-range filters—that allow end-users to define their own views easily.
- User-Specific Data: Automatically restrict data visibility so users only see their own records based on their login credentials.
- Seamless Security: Built-in security functions allow for easy integration with your project’s existing authentication system.
- Mobile-Optimized: Simply toggle the "mobile" layout property to deliver responsive, sturdy reports across all devices.

### Customization & Support

- Tailored Branding: Fully customize your report’s style, layout, headers, and footers, with full support for multilingual labeling.
- Expert Assistance: Access personalized, professional support through our dedicated ticketing system to ensure your project’s success.

---

## 🚀 Features

- PHP MySQL reporting engine  
- Multi-level grouping  
- Subtotals and grand totals  
- Calculated fields  
- Filters & parameters  
- Conditional formatting  
- Responsive layout  
- Secure user-based filtering  

---

## Comunity Edition vs Pro Edition vs Smart Report Maker

While the Community Edition provides a solid foundation for generating PHP reports for MySQL, you may require more advanced capabilities as your project grows. We offer two premium paths to suit your workflow:

#### 1. [Smart Report Engine - Professional Edition](https://mysqlreports.com/mysql-reporting-tools/smart-report-engine/)

Tailored for developers who need maximum control. This version unlocks the full suite of advanced programmatic features, allowing you to code complex, high-performance reports with deeper customization and extended library methods. To help you better understand the distinctions between the community and professional editions of Smart Report Engine, see the detailed license comparison here:  
http://mysqlreports.com/engine/index.php?post=community

#### 2. [Smart Report Maker](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/)

Perfect for those who prefer a visual workflow. This comprehensive tool allows you to build reports, charts, pivot tables, and interactive dashboards through a visual interface. Once created, you can securely integrate them into your own applications using the powerful Embed Manager.

[![Smart Report Maker](https://mysqlreports.com/wp-content/uploads/2015/01/all.gif)](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/)
<h5 align="center">Screenshot of Different Modules of Smart Report Maker</h5>

#### 👉 See Smart Report Maker in Action

Watch how the [Embed Manager](https://mysqlreports.com/srm-modules-embed-manager/) works or explore the full [Video gallery](https://mysqlreports.com/webuccino-screencasts/) to see everything you can build.

---

## 🖼 Screenshots & Videos

Getting Started Video  
https://www.youtube.com/embed/c2j5uR_mxzs  

Smart Report Maker Demo  
https://www.youtube.com/embed/ZLa24Eo5gmE  

---

## 🎯 Use Cases

- Sales reports  
- Financial summaries  
- Inventory reports  
- Admin dashboards  
- Embedded analytics  

---

## Getting started with Smart Report Engine - PHP Reports Builder for MYSQL - Community Edition

1. Clone the Community Edition repository or download it from:  
   https://mysqlreports.com/engine/documentation/index.php?post=community_install

2. Extract the package. The structure will look similar to:

```sh
├── SmartReportingEngine/
├── sre_config/
│  └── config.php
├── sre_reports/
├── db/
│  └── example.sql
├── examples/
└── sre_bootstrap.php
```

[![Watch the video](https://mysqlreports.com/community.png)](https://www.youtube.com/embed/c2j5uR_mxzs)
<p align="center">Getting started with the community edition of Smart Report Engine - Video Tutorial</p>

3. Import the sample MySQL database:
   - Locate `/db/example.sql` (creates and populates a table named `items`).
   - Choose an existing database or create a new one.
   - Import `example.sql`.

4. Configure the database connection string:
   - Open `sre_config/config.php`.
   - Update host, database, username, and password to match your database.

5. Save `config.php`.

6. Run the examples:
   - Open the example URLs from the `/examples` directory in your browser.
   - Reports generated by Smart Report Engine are saved to `/sre_reports`.

---

## Code Example & Walkthrough

To begin writing your first project, you’ll need a new PHP script. For this tutorial, you can place it in the root directory of the Community Edition, alongside `sre_bootstrap.php`. If you manually downloaded the Community Edition (without Composer), you must require `sre_bootstrap.php` as shown below. If your code is in a different location, adjust the path accordingly.

```php
use SRE\Engine\CustomEngine;
use SRE\Engine\ReportOptions;

require_once "sre_bootstrap.php";

try {

    $report = new ReportOptions();
    $report->select_tables("items")
           ->set_grouping("country")
           ->set_title("Items Per country")
           ->select_all_fields();

    $engine = new CustomEngine($report);
    $report_path = $engine->create_report();

    if ($report_path) {
        header("location: ".$report_path);
        exit();
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
```

Generate this report:  
https://mysqlreports.com/engine/sre-community/sre_reports/rep1691646585168400/rep1691646585168400.php

### Code Walkthrough

- **Namespaces:** Uses `SRE\Engine\CustomEngine` and `SRE\Engine\ReportOptions`.
- **Bootstrap:** Requires `sre_bootstrap.php` for autoloading (manual install).
- **ReportOptions:** Defines report options (some features are exclusive to commercial editions):  
  https://mysqlreports.com/engine/documentation/index.php?post=community
- **CustomEngine:** Generates the report from the `ReportOptions`.
- **create_report():** Produces the report and returns a URL. Generated reports are saved in `/sre_reports`.

---

##  Documentation

https://mysqlreports.com/engine/documentation/  
https://mysqlreports.com/examples/  
https://mysqlreports.com/engine/documentation/index.php?post=troubleshooting  

---

# Frequently Asked Questions (FAQ)

## General

### What is Smart Report Engine?

[Smart Report Engine](https://mysqlreports.com/mysql-reporting-tools/smart-report-engine/) is a PHP reporting library that allows developers to generate professional, dynamic reports from MySQL and MariaDB databases programmatically within their own applications.

### What is Smart Report Maker?

[Smart Report Maker](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/) is a complete visual report builder that allows users to design reports, charts, dashboards, drill-down charts, and KPIs through an easy-to-use web interface without writing code. Then you can embed these reports and dashboards to your app via [Embed Manager](https://mysqlreports.com/srm-modules-embed-manager/).

[![Smart Report Maker](https://mysqlreports.com/wp-content/uploads/2015/01/dashboards1.gif)](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/)
<h5 align="center">Dashboards and charts generated by Smart Report Maker</h5>

### Do I need coding skills to use these products?

- **Smart Report Maker:** No coding required.  
- **Smart Report Engine Pro & Smart Report Engine Community:** Basic PHP knowledge is required.

## Product Differences

### What is the difference between Smart Report Maker and Smart Report Engine editions?

The difference between [Smart Report Maker](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/) and [Smart Report Engine](https://mysqlreports.com/mysql-reporting-tools/smart-report-engine/) is that Smart Report Maker is a complete product for creating professional reports, charts, dashboards, drill-down charts, and KPIs using an easy-to-use visual interface. Once created, you can easily embed these analytic resources into your own products using the built-in [Embed Manager](https://mysqlreports.com/srm-modules-embed-manager/).

[![Smart Report Maker](https://mysqlreports.com/wp-content/uploads/2015/01/01.gif)](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/)
<h5 align="center">Linked Reports generated by Smart Report Maker</h5>

On the other hand, Smart Report Engine is a reporting engine designed for PHP developers who prefer to build reports programmatically by calling the engine’s API directly from their code. Smart Report Engine is available in two editions: the Community Edition, which can be used only in personal or open-source community projects, and the Pro Edition, which can be used in commercial applications and hosted SaaS platforms and provides access to premium features.

## Licensing

### Can I use Smart Report Engine Community in a commercial project?

No. Commercial and SaaS usage require either [Smart Report Engine Pro](https://mysqlreports.com/mysql-reporting-tools/smart-report-engine/) or [Smart Report Maker](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/).

### Can I upgrade later?

Yes. You can upgrade from Smart Report Engine Community to either Smart Report Engine Pro or Smart Report Maker at any time.

### Do licenses expire?

No. All licenses are perpetual.

## Technical

### Which PHP versions are supported?

PHP 7.4 and higher.

### Can I use Laravel?

Yes. Both Smart Report Engine Pro and Smart Report Maker provide Laravel integration.

### Where are generated reports stored?

Inside the `/sre_reports` directory.

### Can I apply custom CSS?

Yes. Reports can be styled using the built-in report classes and a CSS editor.

### Are reports mobile responsive?

Yes. Reports are responsive and mobile-friendly.

## Features

### Can I use filters and parameters?

Yes. Both Smart Report Engine & Smart Report Maker include filters and parameters.

### Can I group data?

Yes. Both Smart Report Engine & Smart Report Maker support multiple grouping levels, subtotals and grand totals.

### Can I schedule reports?

- **Smart Report Maker:** Yes  
- **Smart Report Engine Pro:** Via custom implementation  
- **Smart Report Engine Community:** No  

## Security

### Can I restrict users to their own data?

Yes. You can filter data based on the logged-in user.

### Can I integrate with my authentication system?

Yes. Both Smart Report Engine and Smart Report Maker can integrate with existing authentication systems.

## Deployment

### Can I deploy on shared hosting?

Yes. Both Smart Report Engine and Smart Report Maker can be deployed on shared hosting.

### Can I deploy on VPS or cloud servers?

Yes. Both Smart Report Engine and Smart Report Maker can be deployed on VPS.

## Support

### What support is included?

- **Smart Report Maker & Smart Report Engine Pro:** Professional ticket-based support  
- **Smart Report Engine Community:** Community support

### How do I contact support?

https://mysqlreports.com/open-ticket/

## Documentation & Resources

### Where can I find documentation?

- Smart Report Engine Documentation: https://mysqlreports.com/engine/documentation/  
- Smart Report Maker Documentation: https://mysqlreports.com/smart-report-maker-docs/smart-report-maker-docs/

### Where can I find examples?

https://mysqlreports.com/examples/

## Troubleshooting

### My report is blank. What should I check?

- Database connection  
- SQL syntax  
- PHP error logs  
- File permissions  

### Where can I find troubleshooting guides?

https://mysqlreports.com/engine/documentation/index.php?post=troubleshooting

---

## Customer Support

Contact us here:  
https://mysqlreports.com/open-ticket/

---

## Important links

- Smart Report Maker: https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/
- Embed Manager of Smart Report Maker: https://mysqlreports.com/srm-modules-embed-manager/
- Smart Report Engine Pro: https://mysqlreports.com/mysql-reporting-tools/smart-report-engine/ (Priced at $64)
- More examples about Smart Report Engine: https://mysqlreports.com/examples/
- Detailed license comparison: http://mysqlreports.com/engine/index.php?post=community
- Smart Report Engine Troubleshooting: http://mysqlreports.com/engine/documentation/index.php?post=troubleshooting
- Smart Report Engine documentation (classes/methods/constants): https://mysqlreports.com/engine/documentation/index.php?class=reportoptions
- What's new: https://mysqlreports.com/engine/documentation/index.php?post=new
- Home Page of Smart Report Engine: https://mysqlreports.com/mysql-reporting-tools/smart-report-engine/
- Contact Us: https://mysqlreports.com/open-ticket/

---

## ⭐ Support the Project

- Star the repo  
- Report issues  
- Suggest features  

---

## 📜 License

Smart Report Engine Community Edition License

##### The Community Edition permits you to:

- Experiment with Smart Report Engine (Non-Premium features only).
- Freely utilize Smart Report Engine for personal use.
- Freely integrate Smart Report Engine into free open-source projects as long as you keep our copyright claims.

##### The Community Edition restricts you from:

- Accessing Premium features.
- Integrating Smart Report Engine into commercial or SaaS projects.
- Removing the "Powered by" claim from reports generated by the community edition of Smart Report Maker.

> **NOTE:** By using any of our commercial editions, you are granted access to all premium features, as well as the removal of all community restrictions mentioned above:  
> http://mysqlreports.com/engine/documentation/index.php?post=community

---

## 🏷 Trademark

Smart Report Engine™ is a trademark of Webuccino Inc.

---

## 🤝 Contributing

Fork → Branch → PR

---

## 🔎 Topics

mysql  
mysql-report  
mysql-report-builder  
php-reporting  
analytics  
report-generator  

---

## 👤 Author

Webuccino  
https://mysqlreports.com/about/  

Creating easy-to-use products since 2007
