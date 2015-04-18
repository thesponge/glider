<?php
	require("xmlwriterclass.php");
	require("rss_writer_class.php");

	/*
	 *  First create an object of the class.
	 */
	$rss_writer_object=&new rss_writer_class;
	
	/*
	 *  Choose the version of specification that the generated RSS document should conform.
	 */
	$rss_writer_object->specification='1.0';
	
	/*
	 *  Specify the URL where the RSS document will be made available.
	 */
	$rss_writer_object->about='http://www.phpclasses.org/channels.xml';
	
	/*
	 *  Specify the URL of an optional XSL stylesheet.
	 *  This lets the document be rendered automatically in XML capable browsers.
	 */
	$rss_writer_object->stylesheet='http://www.phpclasses.org/rss1html.xsl';
	
	/*
	 *  You may declare additional namespaces that enable the use of more property
	 *  tags defined by extension modules of the RSS specification.
	 */
	$rss_writer_object->rssnamespaces['dc']='http://purl.org/dc/elements/1.1/';
	
	/*
	 *  Define the properties of the channel.
	 */
	$properties=array();
	$properties['description']='Repository of components and other resources for PHP developers';
	$properties['link']='http://www.phpclasses.org/';
	$properties['title']='PHP Classes repository';
	$properties['dc:date']='2002-05-06T00:00:00Z';
	$rss_writer_object->addchannel($properties);
	
	/*
	 *  If your channel has a logo, before adding any channel items, specify the logo details this way.
	 */
	$properties=array();
	$properties['url']='http://www.phpclasses.org/graphics/logo.gif';
	$properties['link']='http://www.phpclasses.org/';
	$properties['title']='PHP Classes repository logo';
	$properties['description']='Repository of components and other resources for PHP developers';
	$rss_writer_object->addimage($properties);
	
	/*
	 *  Then add your channel items one by one.
	 */
	$properties=array();
	$properties['description']='Latest components made available';
	$properties['link']='http://www.phpclasses.org/browse/latest/latest.xml';
	$properties['title']='Latest class entries';
	$properties['dc:date']='2002-05-06T00:00:00Z';
	$rss_writer_object->additem($properties);
	$properties['description']='Latest book reviews released';
	$properties['link']='http://www.phpclasses.org/reviews/latest/latest.xml';
	$properties['title']='Latest reviews';
	$properties['dc:date']='2002-05-06T00:00:00Z';
	$rss_writer_object->additem($properties);
	
	/*
	 *  If your channel has a search page, after adding the channel items, specify a search form details this way.
	 */
	$properties=array();
	
	/*
	 *  The name property if the name of the text input form field on which the user will enter the search word.
	 */
	$properties['name']='words';
	$properties['link']='http://www.phpclasses.org/search.html?go_search=1';
	$properties['title']='Search for:';
	$properties['description']='Search in the PHP Classes repository';
	$rss_writer_object->addtextinput($properties);
	
	/*
	 *  When you are done with the definition of the channel items, generate RSS document.
	 */
	if($rss_writer_object->writerss($output))
	{
		
		/*
		 *  If the document was generated successfully, you may now output it.
		 */
		Header('Content-Type: text/xml; charset="'.$rss_writer_object->outputencoding.'"');
		Header('Content-Length: '.strval(strlen($output)));
		echo $output;
	}
	else
	{
		
		/*
		 *  If there was an error, output it as well.
		 */
		Header('Content-Type: text/plain');
		echo ('Error: '.$rss_writer_object->error);
	}
?>