## Analysis of late trains at Thrissur railway station

### Data collection

Data source: https://etrain.info/in

```bash
php -S localhost:3000
```

Make changes you need in `collect.js.php` and run this in the console

```bash
$.get("http://localhost:3000/collect.js.php", function(data) { (1, eval)(data) })
```

Data will be stored in `state.json`
