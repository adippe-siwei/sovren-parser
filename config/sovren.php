<?php

return [

    'sovren-accountid' => env('SOVREN_ACCOUNT_ID', ''),
    'sovren-servicekey' => env('SOVREN_SERVICE_KEY', ''),
    'sovren-endpoint' => env('SOVREN_ENDPOINT', 'https://eu-rest.resumeparsing.com/v9/parser/resume'),

    /** Fields to be parsed for main information */
    'parseConfigMain' => [
        'firstname' => 'StructuredXMLResume.ContactInfo.PersonName.GivenName',
        'lastname' => 'StructuredXMLResume.ContactInfo.PersonName.FamilyName',
        'Country' => 'StructuredXMLResume.ContactInfo.ContactMethod.*PostalAddress.CountryCode',
        'Zip' => 'StructuredXMLResume.ContactInfo.ContactMethod.*PostalAddress.PostalCode',
        'City' => 'StructuredXMLResume.ContactInfo.ContactMethod.*PostalAddress.Municipality',
        'Address' => 'StructuredXMLResume.ContactInfo.ContactMethod.*PostalAddress.DeliveryAddress.AddressLine.0',
        'MobilePhone' => 'StructuredXMLResume.ContactInfo.ContactMethod.*Mobile.FormattedNumber',
        'HousePhone' => 'StructuredXMLResume.ContactInfo.ContactMethod.*Telephone.FormattedNumber',
        'Email' => 'StructuredXMLResume.ContactInfo.ContactMethod.*InternetEmailAddress',
        'Raw' => 'NonXMLResume.TextResume',
    ],
    /** Fields to be parsed for each jobs */
    'parseConfigJobs' => [
        'Title' => 'PositionHistory.0.Title',
        'Comment' => 'PositionHistory.0.Description',
        'Company' => 'EmployerOrgName',
        'From' => 'PositionHistory.0.StartDate.Year',
        'To' => 'PositionHistory.0.EndDate.Year'
    ],
    /** Fields to be parsed for each diploma */
    'parseConfigDiploma' => [
        'School' => 'School.0.SchoolName',
        'Title' => 'Degree.0.DegreeName',
        'Date' => 'Degree.0.DegreeDate.Year'
    ]
];
