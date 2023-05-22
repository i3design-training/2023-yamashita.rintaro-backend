## how to run

```console
php -S 127.0.0.1:8000 -t public
```

## environment

Homebrewを用いた環境構築を行う

### Homebrew をインストールする

すでに Homebrew がインストールされている場合は、次のPHP をインストールに進んでください。

```console:コマンド
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

### PHP をインストールする
```console:コマンド
brew install php
```

#### 確認

PHP がインストールされていることを確認します。

```console:コマンド
php -v
```

```console:結果例
PHP 8.2.6 (cli) (built: May 11 2023 12:51:38) (NTS)
Copyright (c) The PHP Group
Zend Engine v4.2.6, Copyright (c) Zend Technologies
    with Zend OPcache v8.2.6, Copyright (c), by Zend Technologies
```
    