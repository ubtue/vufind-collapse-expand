# Notes on Solr field types / Indexing

For a quick & dirty test with the `biblio` index, you can just use the default `title_sort` field for `collapse.field` as well as `expand.field`.
For debugging, it might also make sense if you enable a facet for this field in `facets.ini`. This way you can easily find similar records that will be affected by the functionality. Note that the shown numbers in the facet will only make sense if collapse is disabled, else you will only see count=1 for every facet entry.

For productive use, it usually makes sense to define a custom field for this in your index, and also use a custom java import routine that combines multiple parts of your metadata.
Unfortunately Collapse & Expand only supports `Solr.StrField`, so we cannot use Tokenizers & Filters like in `Solr.TextField`.
For the import, a good point to get started is to look at the default optional `work_keys_str_mv` field. However, since Collapse & Expand works only on single-valued fields, there will be some adjustments needed.

```
work_keys_str_mv = custom, getWorkKeys(130anp:730anp, 240anpmr:245abn:246abn:247abn, 240anpmr:245abn, 100ab:110ab:111ac:700ab:710ab:711ac, "", "", ":: NFD; :: lower; :: Latin; :: [^[:letter:] [:number:]] Remove; :: NFKC;")
```

Note that this field already uses normalizations like lowercase, removing all non-letters & numbers, and NFD/NFKC Unicode Normalization. Additional information can be found in the [VuFind Wiki](https://vufind.org/wiki/configuration:record_versions), which also contains information about other similar mechanisms like FRBR.

Another option might be to take a multi-layered approach:
- Search for persistent identifiers (e.g. DOI, LCCN, ...), and skip the regular hashing if at least one persistent identifier is available
    - This only makes sense if you have high metadata quality and all entries of your potential groups contain the same identifier. For example, if you want to collapse print/online records, and e.g. DOIs are only present in your online records, you might need additional preprocessing).
- As fallback, build a custom hash similar to `work_keys_str_mv` (e.g. title, subtitle, authors, ...) or even call `getWorkKeys()` and check whether more normalization is needed.
    - Since getWorkKeys() works with a multiValued field, you might need to concatenate the returned values into a single has
    - In addition, you could append the `format` field at the end of your hash with a delimiter to avoid books being collapsed with articles in some edge cases, and so on.

Experimental: If you really have a big index and run into performance problems, Collapse & Expand in theory also supports Int + Float based data types. So you could try to define Int-based group IDs based on your hash. This could lead to slow indexing, but very fast queries.
