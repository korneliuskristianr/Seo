<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/" xmlns:moz="http://www.mozilla.org/2006/browser/search/">
<ShortName>{{$shortname}}</ShortName>
<Description>{{$description}}</Description>
<InputEncoding>UTF-8</InputEncoding>
<Image width="16" height="16" type="image/x-icon">{{ $iconLink }}</Image>
{{-- e.g: https://github.com/search?q={searchTerms}&ref=opensearch --}}
<Url type="text/html" method="get" template="{{$template}}"/>
<moz:SearchForm>{{$searchform}}</moz:SearchForm>
</OpenSearchDescription>