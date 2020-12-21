# ☁ Soundcloud PHP Api Library 

Mini library for work with Soundcloud API.

## Instalation

With [Composer](https://getcomposer.org/):
```bash
composer require aethletic/soundcloud
```

You can also [download latest release](https://github.com/aethletic/soundcloud/releases) without Composer and include like:
```php 
require __DIR__ . '/soundcloud/src/Api.php';
```


## Documentation
See Soundcloud API documentation [here](https://developers.soundcloud.com/docs/api/reference).


## Examples

**Init:**
```php
<?php 

use Soundcloud\Api;

require __DIR__ . '/vendor/autoload.php';

$sc = new Api('YOUR_CLIENT_ID_HERE');
```

**Iniversal Soundcloud Api call:**
```php
$response = $sc->api(['resolve'], [
    'url' => 'https://soundcloud.com/uvulauvula/sets/snova-vozvrashchayus-domoy-f-pasosh',
]);
```
**Search tracks:**
```php
$response = $sc->api(['tracks'], [
    'q' => 'платина бандана',
]);
```

**Get Track ID:**
```php
$trackId = $sc->getTrackId('https://soundcloud.com/molchat-doma/discoteque');
```

**Get stream links (mp3, hls):**
```php
$trackId = $sc->getTrackId('https://soundcloud.com/pureflavoor/skriptonit-polozhenie-pureflavor-remix');
$streams = $sc->api(['i1', 'tracks', $trackId, 'streams']);

// or you can use short alias for this:
$streams = $sc->getStreamByTrackUrl('https://soundcloud.com/pureflavoor/skriptonit-polozhenie-pureflavor-remix');
```

**Get playlist:**
```php
$playlist = $sc->getPlaylist('https://soundcloud.com/uvulauvula/sets/snova-vozvrashchayus-domoy-f-pasosh');
```

**Create M3U playlist:**
```php
$playlist = $sc->getPlaylist('https://soundcloud.com/uvulauvula/sets/snova-vozvrashchayus-domoy-f-pasosh');
$m3u = $sc->playlistToM3U($playlist);
file_put_contents(__DIR__.'/playlist.m3u', $m3u);
```

**Output file playlist.m3u:**
```m3u
#EXTM3U

#EXTINF:222,увула - Все Не Так (f. Пасош)
https://cf-media.sndcdn.com/46kPhMmaMEFg.128.mp3?Policy=eyJT...

#EXTINF:186,увула - Твои Слова (f. Пасош)
https://cf-media.sndcdn.com/pJBpYQmL9gro.128.mp3?Policy=eyJT...

#EXTINF:286,увула - Больше Не Вернуть (f. Пасош)
https://cf-media.sndcdn.com/HgN976efZQqs.128.mp3?Policy=eyJT...

#EXTINF:213,увула - Искать Себя (f. Пасош)
https://cf-media.sndcdn.com/O5nmdl1mggxd.128.mp3?Policy=eyJT...

#EXTINF:231,Стоп-Слово (f. Пасош)
https://cf-media.sndcdn.com/scLGY2hPpg9l.128.mp3?Policy=eyJT...

#EXTINF:203,увула - Ночная Смена (f. Пасош)
https://cf-media.sndcdn.com/3kaMY43FLmCk.128.mp3?Policy=eyJT...

#EXTINF:168,увула - Несчастный Случай (f. Пасош)
https://cf-media.sndcdn.com/AZsnSLf3fHId.128.mp3?Policy=eyJT...

#EXTINF:156,увула - Снова Возвращаюсь Домой (f. Пасош)
https://cf-media.sndcdn.com/WI2cYB4AJZ4G.128.mp3?Policy=eyJT...
```

