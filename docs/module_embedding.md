# Enabling the module

Here is the step by step guidance to enable this module:

## Embedding the module

1. Add the following line to `composer.json`:
    `"ubtue/vufind-collapse-expand": "@dev"`
    Note that a more concise versioning schema will be introduced as soon as the first release is created (for matching VuFind version).

2. Update composer package (via terminal):
    `composer update`

3. Add the module to `application.config.php` in the config folder.

    ```
    $module = [
    ...
    'VuFindCollapseExpand',
    ...
    ]
    ```

    For advanced users, it's also possible to copy the into `modules` and enable it in `httpd-vufind.conf`.
    But then you need to care about updates yourself.

4. Add the trait
    In your record driver `RecordDriver/SolrDefault` (ex. RecordDriver/SolrDefault.php), there are several changes necessary:

    ```php
    class SolrDefault extends \VuFind\RecordDriver\SolrMarc implements \VuFindCollapseExpand\Config\CollapseExpandConfigAwareInterface
    {
        ...
        use \VuFindCollapseExpand\RecordDriver\Feature\CollapseExpandTrait;
        use \VuFindCollapseExpand\Config\CollapseExpandConfigAwareTrait;
        ...
    }
    ```

## Configure the module

Add the config to `config.ini`

```ini
; The mandatory fields are collapse.field, expand.field, and expand.rows. It is better to set the same value for collapse.field and expand.field.
; When the collapse.field is set, the feature is active.
; If you want to override defaults / use specific features, please have a look at the Solr Documentation:
; https://solr.apache.org/guide/solr/latest/query-guide/collapse-and-expand-results.html
; collapse
; mandatory fields are collapse.field, expand.field and expand.rows. The collapse.field is recommended to set the same value with expand.field
[CollapseExpand]
collapse.field = title_sort
;collapse.min =
;collapse.max =
;collapse.sort =
;collapse.nullPolicy = ignore
;collapse.hint =
;collapse.size = 100000
;collapse.collectElevatedDocsWhenCollapsing = true

expand.field = title_sort
expand.rows = 500
;expand.sort = score desc
;expand.q =
;expand.fq =
;expand.nullGroup = false
```

## Setup and configuration of user interface 

### Mixin Embedding

Add the mixin to your custom theme by referencing it in your `theme.config.php`:
`'mixins' => ['vufind-collapse-expand']`

Experimental: The corresponding folder should be auto-created during composer installation, but if they are missing, just copy them manually from or create a symlink to:
`vendor/ubtue/vufind-collapse-expand/res/theme` => `themes/vufind-collapse-expand`

### Template includes and overrides

The following sections contain information about how to include certain snippets. You might as well also extend/override them in your own theme if necessary.

#### Search result list

* **Checkbox** (search control) for en-/disbaling the grouping 

    Add a reference in your search/results.phtml to the result-list-snippet.phtml
    Copy the code in the file `res/theme/templates/search/controls/collapse_expand.html` where you want the checkbox for enabling/ disabling CollapseExpand dynamically, for example in `[your_theme]/templates/search/results.html`

* enrich **single result** with grouped items

    Add a reference in your result-list.phtml to the result-list-snippet.phtml
`<?=$this->render('RecordDriver/DefaultRecord/result-list-snippet.phtml')?>`

    A good point for adding this include would be at the bottom of `<div class="media-body">`, right before the `</div>`.

#### Record Tab

CollapseExpand comes with a record tab called `Other Document` to show the expand documents when user access the detail information of the record. Using the feature is simple, just follow the instruction below to activate.

`RecordTabs.ini` (`config/vufind/RecordTabs.ini`)
```ini
[VuFind\RecordDriver\SolrMarc]
...
tabs[CollapseExpand] = CollapseExpand
...
```

#### Language Translation

Adding the translation into `[language].ini` for example the english translation:

```ini
...
collapse results = "Collapse similar items"
expand results = "Expand similar items"
```

Note: This might not be necessary if the mixin is included properly, unless you want to override the default display texts.
