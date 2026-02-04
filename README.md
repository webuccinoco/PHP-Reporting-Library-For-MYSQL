# Smart Report Engine - PHP Reports Builder for MYSQL - Community Edition
Automate your data presentation with Smart Report Engine, a dedicated library for building professional PHP reports for MySQL and MariaDB programmatically within your existing codebase.

[![Watch the video](https://mysqlreports.com/video.png)](https://www.youtube.com/embed/ZLa24Eo5gmE)


## Why is Smart Report Engine a powerful PHP Reporting Library for MYSQL?
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

## Advanced Features for Complex Requirements
While the Community Edition provides a solid foundation for generating PHP reports for MySQL, you may require more advanced capabilities as your project grows. We offer two premium paths to suit your workflow:

 #### 1. [Smart Report Engine - Professional Edition](https://mysqlreports.com/mysql-reporting-tools/smart-report-engine/)
  Tailored for developers who need maximum control. This version unlocks the full suite of advanced programmatic features, allowing you to code complex, high-performance reports with deeper customization and extended library methods.  To help you better understand the distinctions between the community and professional editions of smart Report Engine, we have provided a concise overview. [Please check the detailed license comparison here](http://mysqlreports.com/engine/index.php?post=community)

 #### 2. [Smart Report Maker](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/)
  Perfect for those who prefer a visual workflow. This comprehensive tool allows you to build reports, charts, pivot tables, and interactive dashboards through a visual interface. Once created, you can securely integrate them into your own applications using the powerful Embed Manager. 

[![Smart Report Maker](https://mysqlreports.com/wp-content/uploads/2015/01/all.gif)](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/)
<p align="center"><strong></strong>Screenshot of Different Modules of Smart Report Maker</strong></p>
  
  #### See [Smart Report Maker](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/) in Action: Watch how the [Embed Manager](https://mysqlreports.com/srm-modules-embed-manager/) works here or explore our full [Video gallery](https://mysqlreports.com/webuccino-screencasts/) to see everything you can build.  

## Getting started with Smart Report Engine - PHP Reports Builder for MYSQL - Community Edition

1- You have the option to either clone the community edition of Smart Report Engine or download it directly from [this download page](https://mysqlreports.com/engine/documentation/index.php?post=community_install) 

2- After downloading, extract the compressed folder to reveal the structure of SmartReportEngine. The contents will resemble something similar to the following:

```sh
├── SmartReportingEngine/
├── sre_config/
   └── config.php
├── sre_reports/
├── db/
  └── example.sql
├── examples/
└── sre_bootstrap.php
```

[![Watch the video](https://mysqlreports.com/community.png)](https://www.youtube.com/embed/c2j5uR_mxzs)
<p align="center">Getting started with the community edition of Smart Report Engine - Video Tutorial</p>

3- To initiate your first project on your server, you need to import a sample MySQL database. This process will enable you to get started with Smart Report Engine smoothly.
   - Inside the downloaded package of Smart Report Engine, locate the "/db/example.sql" file in the "db" directory. This SQL file contains the necessary commands to create and populate a single MySQL table named 'items'
   - Select the MySQL database for your first project setup. You can opt for an existing database or create a new one specifically for this project.
   - Import the "example.sql" file into your chosen MySQL database. This will create the "items" table and populate it with initial data.
   
4- Configure the database Connection String
   - Navigate to the "sre_config" directory within the Smart Report Engine Community Edition package. There, you will find the "config.php" file. Please open this file using any text editor you prefer.
   - In the "config.php" file, you will find a section dedicated to database configuration. Update the connection string with the appropriate details of the MySQL database where you imported the example SQL file during the 3rd step. Make sure to provide the correct hostname, database name, username, and password in their respective fields.
  
5- Save the changes to the "config.php" file.

6- In the Community Edition's "/examples" directory, you'll find three helpful examples to get you started with Smart Report Engine. To execute any of these examples, just access their URLs from your web browser. This will enable you to view the generated report based on the code in each example.
 
7- Should you prefer to build the example projects from scratch, kindly proceed with step 8 in the installation process.
> **_NOTE:_**  Regardless of your choice, the code walkthrough sections will provide explanations for the code in each example.
 
8- To begin writing your first project, you'll require a new PHP script. For the purpose of this tutorial, you can add the script to the root directory of the community edition, alongside the "sre_bootstrap.php" file. If you manually downloaded the community edition (without using Composer), you'll need to require the "sre_bootstrap.php" autoload filein your code, as demonstrated in the example below. In case your code is placed in different locations for other projects, ensure that you adjust the path to this autoload file accordingly. Please follow these steps to get started with your project:
 - Create a new PHP script for your project.
 - Optionally, place the script in the root directory of the community edition (same level as "sre_bootstrap.php") for this tutorial's purpose.
 - In your first new project, try writing the following simple PHP code. It should work without any issues.

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

[Generate This Report](https://mysqlreports.com/engine/sre-community/sre_reports/rep1691646585168400/rep1691646585168400.php)

## Code Walkthrough: Understanding the Example
The given code demonstrates a basic example of using Smart Report Engine effectively. Let's walk through the code step by step to understand how it functions:
 - **Namespaces:** The given code utilizes two namespaces, "SRE\Engine\CustomEngine" and "SRE\Engine\ReportOptions." These namespaces help organize and access specific parts of the code.
 - **Requiring "sre_bootstrap.php":** If you manually downloaded the community edition (without using Composer), you need to add a special file called "sre_bootstrap.php" to your code.
 - **Creating the ReportOptions Object:** The code initializes an object from the "ReportOptions" class. This object is responsible for defining the options needed for your report. You can customize various options, keeping in mind that [some features are exclusive to the commercial edition.](https://mysqlreports.com/engine/documentation/index.php?post=community)
 - **Passing ReportOptions Object to CustomEngine:** Once you have set your report options, you pass the "ReportOptions" object to the constructor of the "CustomEngine" class. This class handles the creation of your report based on the provided options.
 - **Calling CreateReport Function:** To generate your report, you call the "CreateReport" function using the "CustomEngine" object. This function processes the defined options and generates the report. Upon successful creation, it returns the URL of the report. All the reports generated using Smart Report Engine will be automatically saved in the "sre_reports" directory.

## Community Edition license
##### The Community Edition permits you to:
- Experiment with Smart Report Engine (Non-Premium features only).
- Freely utilize Smart Report Engine for personal use.
- Freely integrate Smart Report Engine into free open-source projects as long as you keep our copyright claims.
##### The Community Edition restricts you from:
- Accessing Premium features.
- Integrating Smart Report Engine into commercial or SaaS projects.
- Removing the "Powered by" claim from reports generated by the community edition of Smart Report Maker.
> **_NOTE:_** By using any of our [commercial editions](http://mysqlreports.com/engine/documentation/index.php?post=community), you are granted access to all premium features, as well as the removal of all community restrictions mentioned above.

# Frequently Asked Questions (FAQ)

## General

### What is Smart Report Engine?
[Smart Report Engine](https://mysqlreports.com/mysql-reporting-tools/smart-report-engine/) is a PHP reporting library that allows developers to generate professional, dynamic reports from MySQL and MariaDB databases programmatically within their own applications.

### What is Smart Report Maker?
[Smart Report Maker](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/) is a complete visual report builder that allows users to design reports, charts, dashboards, drill-down charts, and KPIs through an easy-to-use web interface without writing code. Then you can embed these reports and dashboards to your app via [Embed Manager](https://mysqlreports.com/srm-modules-embed-manager/)

[![Smart Report Maker](https://mysqlreports.com/wp-content/uploads/2015/01/dashboards1.gif)](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/)
<p align="center">Dashboards and charts generated by Smart Report Maker</p>

### Do I need coding skills to use these products?
- **Smart Report Maker:** No coding required.  
- **Smart Report Engine Pro & Smart Report Engine Community:** Basic PHP knowledge is required.

---

## Product Differences

### What is the difference between Smart Report Maker and Smart Report Engine editions?

The difference between [Smart Report Maker](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/) and [Smart Report Engine](https://mysqlreports.com/mysql-reporting-tools/smart-report-engine/) is that Smart Report Maker is a complete product for creating professional reports, charts, dashboards, drill-down charts, and KPIs using an easy-to-use visual interface. Once created, you can easily embed these analytic resources into your own products using the built-in [Embed Manager](https://mysqlreports.com/srm-modules-embed-manager/).

[![Smart Report Maker](https://mysqlreports.com/wp-content/uploads/2015/01/01.gif)](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/)
<p align="center">Linked Reports generated by Smart Report Maker</p>


On the other hand, Smart Report Engine is a reporting engine designed for PHP developers who prefer to build reports programmatically by calling the engine’s API directly from their code. Smart Report Engine is available in two editions: the Community Edition, which can be used only in personal or open-source community projects, and the Pro Edition, which can be used in commercial applications and hosted SaaS platforms and provides access to premium features.

---

## Licensing

### Can I use Smart Report Engine Community in a commercial project?
No. Commercial and SaaS usage require either [Smart Report Engine Pro](https://mysqlreports.com/mysql-reporting-tools/smart-report-engine/) or [Smart Report Maker](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/).

### Can I upgrade later?
Yes. You can upgrade from Smart Report Engine Community to either Smart Report Engine Pro or Smart Report Maker at any time.

### Do licenses expire?
No. All licenses are perpetual.

---

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

---

## Features

### Can I use filters and parameters?
Yes. Both Smart Report Engine & Smart Report Maker include filters and parameters. 


### Can I group data?
Yes. Both Smart Report Engine & Smart Report Maker support multiple grouping levels, subtotals and grand totals.

### Can I schedule reports?
- **Smart Report Maker:** Yes  
- **Smart Report Engine Pro:** Via custom implementation  
- **Smart Report Engine Community:** No  

---

## Security

### Can I restrict users to their own data?
Yes. You can filter data based on the logged-in user.

### Can I integrate with my authentication system?
Yes. Both Smart Report Engine and Smart Report Maker can integrate with existing authentication systems.

---

## Deployment

### Can I deploy on shared hosting?
Yes. Both Smart Report Engine and Smart Report Maker can be deployed on shared hosting. 

### Can I deploy on VPS or cloud servers?
Yes. Both Smart Report Engine and Smart Report Maker can be deployed on VPS.


---

## Support

### What support is included?
- **Smart Report Maker & Smart Report Engine Pro:** Professional ticket-based support  
- **Smart Report Engine Community:** Community support

### How do I contact support?
[https://mysqlreports.com/open-ticket/](https://mysqlreports.com/open-ticket/)

---

## Documentation & Resources

### Where can I find documentation?
 - [Smart Report Engine Documentation ](https://mysqlreports.com/engine/documentation/)
 - [Smart Report Maker Documentation ](https://mysqlreports.com/smart-report-maker-docs/smart-report-maker-docs/)

### Where can I find examples?
[https://mysqlreports.com/examples/](https://mysqlreports.com/examples/)

---

## Troubleshooting

### My report is blank. What should I check?
- Database connection  
- SQL syntax  
- PHP error logs  
- File permissions  

### Where can I find troubleshooting guides?
[https://mysqlreports.com/engine/documentation/index.php?post=troubleshooting](https://mysqlreports.com/engine/documentation/index.php?post=troubleshooting)

---

## Still have questions?

Contact us here:  
[https://mysqlreports.com/open-ticket/](https://mysqlreports.com/open-ticket/)
 
## Important links
 - [Smart Report Maker](https://mysqlreports.com/mysql-reporting-tools/the-best-mysql-report-builder/)
 - [Embed Manager of Smart Report Maker](https://mysqlreports.com/srm-modules-embed-manager/)
 - [Smart Report Engine Pro](https://mysqlreports.com/mysql-reporting-tools/smart-report-engine/) is an advanced version of Smart Report Engine, granting access to all of its premium features. Priced at just $64 
 - [More Examples about Smart Report Engine](https://mysqlreports.com/examples/)
 - [Detailed license comparison](http://mysqlreports.com/engine/index.php?post=community)
 To help you better understand the distinctions between the community and commercial editions, we have provided a concise overview in [this page](http://mysqlreports.com/engine/index.php?post=community). It highlights the key differences in features and use cases for each version.
- [Smart Report Engine Troubleshooting](http://mysqlreports.com/engine/documentation/index.php?post=troubleshooting)
The purpose of this section in the documentation is to guide users through essential checkpoints to consider when encountering any issues during the report generation process with Smart Report Engine. Additionally, we will explore Smart Report Engine's logging feature, which assists in troubleshooting potential problems that may arise in the generated reports.
- [Smart Report Engine documentation](https://mysqlreports.com/engine/documentation/index.php?class=reportoptions)
In this section, you will discover comprehensive documentation and examples for each built-in class, method, and constant of Smart Report Engine.
- [What's new](https://mysqlreports.com/engine/documentation/index.php?post=new) 
This section will encompass the latest features introduced in Smart Report Engine.
- [Home Page of Smart Report Engine](https://mysqlreports.com/mysql-reporting-tools/smart-report-engine/)
- [Contact Us](https://mysqlreports.com/open-ticket/)

## Author
[Webuccino](https://mysqlreports.com/about/) Creating easy-to-use products since 2007
