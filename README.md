# fanfeedrapi

Various client side code for calling the [FanFeedr API](http://developer.fanfeedr.com).

## Example Usage
### PHP
    <?php
        require_once('php/fanfeedr.php');
        $fanfeedr = new FanFeedr('<your-fanfeedr-basic-api-key>', 'basic');
        $search_results = $fanfeedr->search('steelers');
        print "<h1>Steelers News</h1>";
        foreach($search_results->docs as $result){
            print "<a href=\"".$result['article.link']."\">".$result['entity.name']."</a><br />";
        }
    ?>

### Python
    fanfeedr = FanFeedr('<your-fanfeedr-basic-api-key>', 'basic')
    search_results = fanfeedr.search('steelers')
    print "<h1>Steelers News</h1>"
    for result in search_results['docs']:
        print "<a href=\""+result['article.link']+"\">"+result['entity.name']+"</a>"
        
## Future
* ActionScript 3 Library
* Javascript Library
* Java Library
* Perl Library
