<?php

class Cfeed
{

    public
        $C,               //main object
        $lang,
        $idC;

    public function __construct(&$C)
    {/*{{{*/
    }/*}}}*/

    public function _init_()
    {

        error_log("[ivy] Entered the feed module", E_USER_WARNING);

        /*
         *  First create an object of the class.
         */
        $this->rss_writer_object=&new rss_writer_class;

        /*
         *  Choose the version of specification that the generated RSS document should conform.
         */
        $this->rss_writer_object->specification='1.0';

        /*
         *  Specify the URL where the RSS document will be made available.
         */
        $this->rss_writer_object->about=PUBLIC_URL . 'feed';

        /*
         *  Specify the URL of an optional XSL stylesheet.
         *  This lets the document be rendered automatically in XML capable browsers.
         */
        // $rss_writer_object->stylesheet='http://theblacksea.eu/rss1html.xsl';

        /*
         *  You may declare additional namespaces that enable the use of more property
         *  tags defined by extension modules of the RSS specification.
         */
        $this->rss_writer_object->rssnamespaces['dc']='http://purl.org/dc/elements/1.1/';

        /*
         *  Define the properties of the channel.
         */
        $properties=array();
        $properties['description']='A non-profit online multimedia magazine
            that aims to become a regional brand for in-depth journalism
            and photo-reportage.';
        $properties['link']='http://theblacksea.eu/';
        $properties['title']='The Black Sea';
        //$properties['dc:date']='2002-05-06T00:00:00Z';
        $this->rss_writer_object->addchannel($properties);

        /*
         *  If your channel has a logo, before adding any channel items, specify the logo details this way.
         */
        $properties=array();
        $properties['url']=FW_PUB_URL . 'LOCALS/blogSite/tmpl_bsea/css/img/rss_logo.png';
        $properties['link']='http://theblacksea.eu/';
        $properties['title']='The Black Sea';
        $properties['description']='A non-profit online multimedia magazine
            that aims to become a regional brand for in-depth journalism
            and photo-reportage.';
        $this->rss_writer_object->addimage($properties);

        /*
         *  Then add your channel items one by one.
         */

        $this->populateFeed(15);

        /*
         *  If your channel has a search page, after adding the channel items, specify a search form details this way.
         */
        // $properties=array();

        /*
         *  The name property if the name of the text input form field on which the user will enter the search word.
         */
        // $properties['name']='search';
        // $properties['link']='http://theblacksea.eu/search?query=';
        // $properties['title']='Search for:';
        // $properties['description']='Search in The Black Sea';
        // $rss_writer_object->addtextinput($properties);

        /*
         *  When you are done with the definition of the channel items, generate RSS document.
         */
        if($this->rss_writer_object->writerss($output))
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

        exit();
    }

    protected function populateFeed($items=10)
    {
        $query = "SELECT * FROM blogRecords_view
            ORDER BY publishDate DESC
            LIMIT 0, $items";

        $this->DB->set_charset("utf8");
        $result = $this->DB->query($query);

        while($row = $result->fetch_array()) {
            $properties=array();
            $properties['description'] = $row['content'];
            $properties['link']        = 'http://theblacksea.eu/index.php?idT = '
                                        .$row['idTree'].'&idC = '
                                        .$row['idCat'].'&idRec = '
                                        .$row['idRecord'];
            $properties['title']       = $row['title'];
            //$properties['dc:date']     = '2002-05-06T00:00:00Z';
            //$properties['dc:date']     = $row['publishDate'] . 'T00:00:00Z';
            $properties['dc:date']     = $row['publishDate'];

            $this->rss_writer_object->additem($properties);
        }
    }


}
