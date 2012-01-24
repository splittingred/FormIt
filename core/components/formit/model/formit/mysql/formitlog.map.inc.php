<?php
/**
 * @package formit
 */
$xpdo_meta_map['FormItLog']= array (
  'package' => 'formit',
  'table' => 'formit_log',
  'fields' => 
  array (
    'from' => NULL,
    'to' => NULL,
    'cc' => NULL,
    'bcc' => NULL,
    'subject' => NULL,
    'message' => NULL,
    'is_html' => 'Y',
    'sender_ip' => NULL,
    'sender_pcname' => NULL,
    'category' => NULL,
    'status' => 'sent',
    'date_sent' => NULL,
    'from_uri' => NULL,
    'resend_info' => NULL,
  ),
  'fieldMeta' => 
  array (
    'from' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => true,
    ),
    'to' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'cc' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'bcc' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'subject' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
    'message' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'is_html' => 
    array (
      'dbtype' => 'set',
      'precision' => '\'Y\',\'N\'',
      'phptype' => 'string',
      'null' => true,
      'default' => 'Y',
    ),
    'sender_ip' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '39',
      'phptype' => 'string',
      'null' => true,
    ),
    'sender_pcname' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => true,
    ),
    'category' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
      'phptype' => 'string',
      'null' => true,
    ),
    'status' => 
    array (
      'dbtype' => 'set',
      'precision' => '\'sent\',\'failed\',\'resent\'',
      'phptype' => 'string',
      'null' => true,
      'default' => 'sent',
    ),
    'date_sent' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'from_uri' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'resend_info' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
);
