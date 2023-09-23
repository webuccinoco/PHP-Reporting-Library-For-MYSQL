# Smart Report Engine - Community Edition
Smart Report Engine is a user-friendly PHP reporting framework that enables the effortless creation of professional reports programmatically within your projects.

[![Watch the video](https://mysqlreports.com/video.png)](https://www.youtube.com/embed/ZLa24Eo5gmE)

## Getting started



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
[![Watch the video](https://mysqlreports.com/gs.png)](https://www.youtube.com/embed/c2j5uR_mxzs)

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
 ```sh 
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
       // The user will be redirected to the URL of the generated report. All generated reports are stored as subdirectories under /sre_reports.
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
 
## Important links
 -  [More Examples](https://mysqlreports.com/engine/documentation/index.php?post=community_install)
 -  [Detailed license comparison](http://mysqlreports.com/engine/index.php?post=community)
 To help you better understand the distinctions between the community and commercial editions, we have provided a concise overview in [this page](http://mysqlreports.com/engine/index.php?post=community). It highlights the key differences in features and use cases for each version.
- [Smart Report Engine Troubleshooting](http://mysqlreports.com/engine/documentation/index.php?post=troubleshooting)
The purpose of this section in the documentation is to guide users through essential checkpoints to consider when encountering any issues during the report generation process with Smart Report Engine. Additionally, we will explore Smart Report Engine's logging feature, which assists in troubleshooting potential problems that may arise in the generated reports.
- [Smart Report Engine documentation](https://mysqlreports.com/engine/documentation/index.php?class=reportoptions)
In this section, you will discover comprehensive documentation and examples for each built-in class, method, and constant of Smart Report Engine.
- [What's new](https://mysqlreports.com/engine/documentation/index.php?post=new) 
This section will encompass the latest features introduced in Smart Report Engine.
- [Home Page of Smart Report Engine](https://mysqlreports.com/mysql-reporting-tools/smart-report-engine/)
- [Contact Us](https://mysqlreports.com/open-ticket/)

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

## Smart Report Engine Pro
[Smart Report Engine Pro](https://mysqlreports.com/engine/documentation/index.php?post=community_install) is an advanced version of Smart Report Engine, granting access to all of its premium features. Priced at just $64 for a Team License, the Pro edition offers numerous advantages, including:

- Complete access to all Premium features.
- Can be used in SaaS and Commercial projects.
- Effortless integration with any existing session-based login system.
- Absence of a "Powered by" attribution in the footer of generated reports.
- A complimentary year of free upgrades. 
- A 70% discount on future upgrade fees (after the free upgrade period).
- Priority customer support.
- The ability to deploy Smart Report Engine on an unlimited number of servers or projects.
- The Team License allows for use by up to 7 developers, while the Enterprise License permits an unlimited number of developers within the organization.
- Royal free (no extra charged) when you delivered Smart Report Engine with your commercial product.

## Author
[Webuccino](https://mysqlreports.com/about/) Creating easy-to-use products since 2007
