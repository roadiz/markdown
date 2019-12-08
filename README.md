# roadiz/markdown

**Markdown services and Twig extension for Roadiz**

This lib defaults to `league/commonmark` markdown parser.


### Parsedown support
If you want to revert to `erusev/parsedown`, use `\RZ\Roadiz\Markdown\Parsedown` service and make sure to require
*Composer* dependencies in your project:

- `composer require erusev/parsedown`
- `composer require erusev/parsedown-extra`

**Warning:** `erusev/parsedown-extra` is still not compatible with PHP 7.4. If you need PHP 7.4 support, stay with 
default `league/commonmark` libraries.
