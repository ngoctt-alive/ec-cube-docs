Tài liệu phát triển EC-Cube 4
=================================================

[EC-CUBE 4開発ドキュメント](https://doc4.ec-cube.net/)のリポジトリです。
Đây là một kho lưu trữ cho [các tài liệu phát triển EC-Cube 4](https://doc4.ec-cube.net/) .

EC-CUBE 4 の仕様や手順、開発Tipsに関するドキュメントを掲載しています。
Nó chứa tài liệu liên quan đến thông số kỹ thuật, quy trình và mẹo phát triển EC-Cube 4.

修正や追記、新規ドキュメントの作成をいただく場合、
Nếu bạn muốn chỉnh sửa, phụ lục hoặc tạo một tài liệu mới,

本リポジトリへPullRequestをお送りください。
Vui lòng gửi PullRequest đến kho lưu trữ này.

開発協力に関して
Liên quan đến hợp tác phát triển
-------------------------------------------

コードの提供・追加、修正・変更その他「EC-CUBE」への開発の御協力（Issue投稿、PullRequest投稿など、GitHub上での活動）を行っていただく場合には、 [EC-CUBEのコピーライトポリシー](https://github.com/EC-CUBE/ec-cube/wiki/EC-CUBE%E3%81%AE%E3%82%B3%E3%83%94%E3%83%BC%E3%83%A9%E3%82%A4%E3%83%88%E3%83%9D%E3%83%AA%E3%82%B7%E3%83%BC)をご理解いただき、ご了承いただく必要がございます。
Nếu bạn đang cung cấp, thêm, sửa đổi hoặc hỗ trợ cho việc phát triển "EC-Cube" (các hoạt động trên GitHub, chẳng hạn như các vấn đề đăng, PullRequests, v.v.), Bạn cần hiểu và thừa nhận [chính sách bản quyền của EC-Cube](https://github.com/EC-CUBE/ec-cube/wiki/EC-CUBE%E3%81%AE%E3%82%B3%E3%83%94%E3%83%BC%E3%83%A9%E3%82%A4%E3%83%88%E3%83%9D%E3%83%AA%E3%82%B7%E3%83%BC) .

PullRequestを送信する際は、EC-CUBEのコピーライトポリシーに同意したものとみなします。
Khi bạn gửi PullRequest, bạn đồng ý với chính sách bản quyền của EC-Cube.

本ドキュメントサイトの構成ツールについて
Về các công cụ cấu hình cho trang web tài liệu này
-------------------------------------------------------------------------

EC-CUBE 4 開発者向けドキュメントは[github pages](https://pages.github.com/)でホスティングされています。
Tài liệu nhà phát triển EC-Cube 4 được lưu trữ trên [các trang GitHub](https://pages.github.com/) .

また、[Jekyll](http://jekyllrb-ja.github.io/)の[Minimal Mistakes Jekyll theme](https://mmistakes.github.io/minimal-mistakes/)というテーマを利用しています。
Chúng tôi cũng sử dụng chủ đề của [Jekyll](http://jekyllrb-ja.github.io/) , [chủ đề sai lầm tối thiểu Jekyll](https://mmistakes.github.io/minimal-mistakes/) .

PullRequestの送信方法
Cách gửi PullRequest
---------------------------------------

Githubアカウントを作成し、自身のリポジトリよりPullRequestを作成してください。
Tạo một tài khoản GitHub và tạo PullRequest từ kho lưu trữ của bạn.

### doc4.ec-cube.netをForkする
Fork Doc4.ec-cube.net

doc4.ec-cube.netのリポジトリをご自身のGithubリポジトリにForkします。
Fork the Doc4.ec-cube.net Kho lưu trữ vào kho GitHub của bạn.

### 任意のディレクトリにクローンする
Nhân bản vào bất kỳ thư mục nào

Forkしたご自身のリポジトリからソースを、`git clone` コマンドで自身のPCにコピーします。
Sao chép nguồn từ kho lưu trữ của riêng bạn vào PC của riêng bạn bằng lệnh `git clone` .

$ git clone https://github.com/[ご自身のアカウント名]/doc4.ec-cube.net.git
```

### リモートリポジトリに本家のリポジトリを登録する

本家のリポジトリの名前を`upstream`（任意）で登録します。

```
$ cd doc4.ec-cube.net/
$ git remote add upstream https://github.com/EC-CUBE/doc4.ec-cube.net.git
```

### ローカルのブランチを最新し、修正用のブランチを作成する
```
$ git pull upstream master
$ git checkout -b [任意のブランチ名]
```

### ドキュメント編集について

#### 本文の編集

_pages/以下のディレクトリにある.mdファイルを変更することで、ページの編集が可能です。

#### サイドバーの編集

_data/navigation.ymlに設定項目を追加します。

#### 設定ファイル

_config.ymlはサイト全体に適用されている設定ファイルです。

### 自身のリポジトリに修正内容を反映する

```
$ git add [修正したファイル]
$ git commit -m "[コメント]"
$ git push origin [ブランチ名]


その後、本家のリポジトリに自身のGithubリポジトリよりPullRequestを作成してください。
Sau đó, tạo một PullRequest từ kho lưu trữ GitHub của bạn trong kho lưu trữ ban đầu của bạn.

修正したドキュメントをローカル環境で確認するには
Để xem các tài liệu được sửa đổi cục bộ
------------------------------------------------------------------

ローカル開発環境を構築することにより、
Bằng cách tạo ra một môi trường phát triển địa phương,

ドキュメントを修正した場合際に、ローカルPCで変更箇所を確認することができます。
Khi bạn chỉnh sửa tài liệu, bạn có thể kiểm tra các thay đổi trên PC cục bộ của bạn.

### Dockerを利用する
Sử dụng Docker

[Docker Compose](http://docs.docker.jp/compose/toc.html) がインストールされていればより簡単な方法でドキュメントを生成できます。 コマンドを実行後、 ブラウザで `http://localhost:4000` にアクセスしてください。
[Docker Compose](http://docs.docker.jp/compose/toc.html) được cài đặt và bạn có thể tạo tài liệu theo cách đơn giản hơn. Sau khi chạy lệnh, vui lòng truy cập `http://localhost:4000` trong trình duyệt của bạn.

# ディレクトリ移動
$ cd doc4.ec-cube.net

# サーバを起動します。(初回)
# * 起動するまでに多少時間がかかります。ご注意ください。
# * マークダウンファイルを編集すれば数秒後にHTMLの再生成が行われます。
$ docker-compose up

# サーバを停止します。
$ docker-compose stop

# サーバを起動します。(二回目以降)
$ docker-compose start


Windows、Macの環境で動作確認済みです。
Nó đã được xác nhận để làm việc trên môi trường Windows và Mac.

### ローカルのRuby環境を利用する
Sử dụng môi trường ruby ​​địa phương

#### 前提条件
Điều kiện tiên quyết

1. ローカル環境にruby(バージョン：2.4.0以上)がインストールされている必要があります。
Ruby (phiên bản: 2.4.0 trở lên) phải được cài đặt trong môi trường địa phương của bạn.
2. Windows環境の場合、Git Bash等のターミナルを利用して下さい。
Nếu bạn đang sử dụng môi trường Windows, vui lòng sử dụng thiết bị đầu cuối như Git Bash.
3. ご自身のGithubアカウントが必要になります。
Bạn sẽ cần tài khoản GitHub của riêng bạn.

※ Rubyのバージョン確認方法
\*Cách kiểm tra phiên bản Ruby

$ ruby -v
ruby 2.4.5p335 (2018-10-18 revision 65137) [x64-mingw32]
```

#### gem（rubyのライブラリ）のインストールを行う

`bundle install`により、gemfile.lockを元にgemのインストールを行います。

```
$ bundle install


※ Windows環境では、gemfile.lockが更新されてしまいますが、
\* Trong Windows Môi trường, Gemfile.Lock sẽ được cập nhật,

git管理から除外（コミット対象から除外する）するように下さい。
Vui lòng loại trừ khỏi Git Management (loại trừ khỏi cam kết).

eventmachine (1.2.7-x64-mingw32)


#### ローカルサーバーでサイトを立ち上げる
Bắt đầu một trang web trên máy chủ cục bộ

以下のコマンドでサイトが立ち上がります。
Lệnh sau đây sẽ khởi chạy trang web:

\$ bundle exec jekyll serve
（省略）
Server address: http://localhost:4000
Server running... press ctrl-c to stop.


[http://localhost:4000](http://localhost:4000) にブラウザのURLでアクセスすると、 EC-CUBE 4開発ドキュメントのページが表示されます。
Nếu bạn truy cập [http: // localhost: 4000](http://localhost:4000) bằng URL trình duyệt, trang tài liệu phát triển EC-Cube 4 sẽ được hiển thị.

