
* londonResponseOk.1.json -- partial JSON & manually fixed
* curl  "https://global.atdtravel.com/api/products?geo=en&title=london" | tee atdtravel.1.json
* curl  "https://global.atdtravel.com/api/products?geo=en-ie&title=dublin"  - "No products found."
* curl  "https://global.atdtravel.com/api/products?geo=en-ie&title=london" | tee atdtravel.ie2-london.json
* curl  "https://global.atdtravel.com/api/products?geo=en&title=london&limit=19&offset=0" | tee atdtravel.3-london.limit19offset0.json
* curl  "https://global.atdtravel.com/api/products?geo=en&title=london&limit=19&offset=19" | tee atdtravel.3-london.limit19offset19.json
