Entry parser bundle
===================

That's the documentation about the entry parser bundle.

1) Introduction
---------------

The entry parser extracts the tags and names from a content 


2) Usage of the parsing service
-------------------------------

    $post = <<<POST
        some very lon post #tag @name
    POST;
    
    $extractor = new \Sententiaregum\Bundle\EntryParsingBundle\Parser\EntryParser;
    $tagList = $extractor->extractTagsFromPost($post);
    $nameList = $extractor->extractNamesFromPost($post);
    
    var_dump($tagList);
    var_dump($nameList);

The output will be:

    array(1) {
      [0]=>
      string(4) "name"
    }
    
    array(1) {
      [0)=>
      string(3) "tag"
    }


3) Options
----------

Sometimes you don't want to use # - signs for tags or @ - signs for names:

    $extractor = new \Sententiaregum\Bundle\EntryParsingBundle\Parser\EntryParser('your custom tag delimiter', 'your custom name delimiter');

The first char of the default sign will be stripped be default. If you don't want to - just disable the option:

    $extractor = new \Sententiaregum\Bundle\EntryParsingBundle\Parser\EntryParser('#', '@',  false);


4) Symfony 2
------------

You can configure the extractor over the *app/config.yml*

    sententiaregum_entry_parsing:
      tag_delimiter: "#" # you need to write the # in two quotes or it will be interpreted as comment!
      name_delimiter: @
      strip_delimiter: true

Now you can access the service via:

    $extractor = $this->container->get('sen_parser.entry_parser');
