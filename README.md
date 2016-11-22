# OBS QtWebkit CMD #

Generar 20 instancias:

```
php app/console obsqtwebkit:generate --quantity 20
```

Generar 5 instancias desde el numero 21 (qtwebkit-browser-21 => qtwebkit-browser-25)

```
php app/console obsqtwebkit:generate --quantity 5 --startat 21
```