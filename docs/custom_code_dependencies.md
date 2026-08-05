# Enabling the VuFindCollapseExpand module along custom code modules

The VuFindCollapseExpand module extends several VuFind classes. Therefore, if you have added a module with custom code to your VuFind installation which customizes any of the following classes you need to list the VuFindCollapseExpand module in the `application.config.php` prior to your custom module and alter the inheritance references to the VuFindCollapseExpand module accordingly.

VuFind classes extended in VuFindCollapseExpand module:

```php
\VuFind\AjaxHandler\AbstractBase
\VuFind\Controller\SearchController
\VuFind\Search\Factory\AbstractSolrBackendFactory
\VuFind\Search\Solr\Params
\VuFind\ServiceManager\ServiceInitializer
\VuFindSearch\Backend\Solr\Backend
\VuFindSearch\Backend\Solr\Response\Json\RecordCollection
```
