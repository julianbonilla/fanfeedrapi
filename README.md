= fanfeedrapi

Various client side code for calling the [FanFeedr API](http://developer.fanfeedr.com)

== PHP
    <?php
        require_once('php/fanfeedr.php');
        $fanfeedr = new FanFeedr('basic', '<your-fanfeedr-basic-api-key>');
        $search_results = $fanfeedr->search('steelers');
        print "<h1>Steelers News</h1>";
        foreach($search_results->docs as $result){
            print "<a href=\"".$result['article.link']."\">".$result['entity.name']."</a><br />";
        }
    ?>