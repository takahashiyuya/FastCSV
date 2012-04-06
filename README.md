#FastCSV
* version 0.8

##条件
* CakePHP 2.x.x
* mb（マルチバイト文字列）が使用できる環境

##機能
* 数行のコードでCSV化を可能にする fastExport()
* その他一般的なCSVヘルパー機能

##ダウンロード
* このプラグインはGitHubにて公開されています。
    *https://github.com/takahashiyuya/FastCSV/downloads

##インストール
* PluginフォルダにFastCSVフォルダを設置します。
* app/Config/bootstrap.php にて以下の記述を行う。
    * CakePlugin::load('FastCSV');
* 目的のコントローラにて以下の記述を行う。
    * public $helpers = array('FastCSV.FastCSV');

##注意点
* CakePHPのfindでデータを抽出する際、recursive = 0 にする事を忘れないでください。
* 目的のアクションにて以下の記述すると問題が解消するかもしれません。
    *Configure::write('debug', 0);
    *$this->layout = false;

##ライセンス
* MIT License (http://www.opensource.org/licenses/mit-license.php)

##コピーライト
* Copyright 2012, Yuya Takahashi([@takahashiyuya](https://twitter.com/#!/takahashiyuya "twitter:@takahashiyuya")).