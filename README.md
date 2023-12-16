## Analysis of late trains at Thrissur railway station

### Analysis

See https://lab.subinsb.com/late-trains-at-indian-railway-stations/ (2023 November 10 - 2023 December 9)

https://twitter.com/SubinSiby/status/1735979264099643589

### Data collection

Data source: https://etrain.info/in

```bash
cp state.template.json state.json # Clean state to start from scratch
php -S localhost:3000
```

Make changes you need in `collect.js.php` and run this in the console

```bash
$.get("http://localhost:3000/collect.js.php", function(data) { (1, eval)(data) })
collectorInit()
setInterval(fetchDelays, 6000)
```

Data will be stored in `state.json`

Live analysis as the data is collected can be seen from http://localhost:3000
