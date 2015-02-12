#!/usr/bin/perl -w 
# titlebytes - find the title and size of documents 
use Switch;
use LWP::UserAgent; 
use HTTP::Request; 
use HTTP::Response; 
use URI::Heuristic;
use DBI;


$dbh = DBI->connect('DBI:mysql:u2166667_rob', 'u2166_sakhnov', 'fuckyouYeah'
	           ) || die "Could not connect to database: $DBI::errstr";
# (insert query examples here...)
$sth = $dbh->prepare('SELECT * FROM product WHERE model like ?');
$sth_update = $dbh->prepare('UPDATE product set price = ?, stock_status_id = ?, quantity = ? WHERE product_id = ?');


my $raw_url = "http://qrobot.ru/robot-pylesos/?limit=100"; 
my $url = URI::Heuristic::uf_urlstr($raw_url);
$| = 1;                                  # to flush next line 
printf "%s =>\n\t", $url;
my $ua = LWP::UserAgent->new(); 
$ua->agent("SuperDoggy/v0.0x WoodEdition"); # give it time, it'll get there
my $req = HTTP::Request->new(GET => $url); 
$req->referer("http://qrobot.ru");
                                         # perplex the log analysers
my $response = $ua->request($req);
if ($response->is_error()) {
     printf " %s\n", $response->status_line;
 } else {
     my $count;
     my $bytes;
     my $content = $response->content();
     $bytes = length $content;
     $count = ($content =~ tr/\n/\n/);
     printf "%s (%d lines, %d bytes)\n", $response->title(), $count, $bytes; 
     
     #find product links
     my @links = ($content =~ m/<div class\=\"name\"><a href\=\"([^\"]+)\">([^<]+)<\/a><\/div>/g );
#     print join ("\n", @links);
	my $countElement = 0;
	my $updated = 0;
	my @notFoundModels = ();
	printf "total %d\n\n", scalar(@links)/2;

     foreach $link (@links) {
      	
     	if ($countElement++ % 2 == 1) {
	     	$productName = $link;
	     	next;
     	}
#     	next if ($countElement < 60);
#     	sleep(1);
     	printf "\n\n%d) get & parse: %s (uri: %s)\n", $countElement/2, $productName, $link;
     	$req->uri($link);
     	my $productResponse = $ua->request($req);
     	if ($productResponse->is_error()) {
	     	printf " %s\n", $productResponse->status_line;
	     } else {
		     my $productContent = $productResponse->content();
		     if ($productContent =~ m/<span>Наличие\:<\/span>\s*<span class\=\"([^\"]+)\">([^<]+)<\/span><\/div>\s*<div class\=\"price\">Цена\:\s*(((\d+)\s*руб\.)|<span class\=\"price\-old\">(\d+)\s*руб\.<\/span>\s*<span class\=\"price\-new\">(\d+) руб\.<\/span>)\s*<br/s) {
			     my $aval = $2;
			     my $price = $3;
#			     print "info: ", "\t", $1, "\t", $2, "\t", $3, "\t", $4,"\t", $5,"\t", $6, "\n";
			     if ($price =~ m/<span class\="price\-old\">(\d+) руб.<\/span> <span class=\"price\-new\">(\d+) руб\.<\/span>/) {
				     printf "old price: %d, new price: %d \n", $1, $2, "\n";
				     $price = $2;
			     } else {
				     printf "one price %d\n", $5;
				     $price = $5;
			     }
			     if ($productContent =~ m/<span>Модель\:<\/span>\s*([^<]+)\s*<br/) {
				     $model = $1;
			     } else {
				     $model = "no model";
			     }
			     $model =~ s/\s*Робот\-пылесос\s*//;
			     $model =~ s/\’/\\\’/;
			     printf "model name: %s\n", $model;
			     $sth->execute(sprintf("%%%s%%", $model));
			     if ($result = $sth->fetchrow_hashref()) {
				 $avail_amount = 0;   
				  
				     switch ($aval) {
					     case "Есть на складе" { $stockStatus = 7; $avail_amount =  int(rand(90)) + 10; }
					     case "Через 2-3 дня" { $stockStatus = 6; }
					     case "Предзаказ" { $stockStatus = 8; }
					     case "Нет на складе" { $stockStatus = 5; }
					     else { $stockStatus = $result->{stock_status_id}}
				     }
				     if ($sth_update->execute($price, $stockStatus, $avail_amount, $result->{product_id})) {
					     printf "model %s updated with price %d (%d) and stock status %d (%d)\n", $model, $price, $result->{price}, $stockStatus, $result->{stock_status_id};
					     $updated++;
				     }
				     
			     } else {
				     printf "model %s was not found!\n", $model;
				     push @notFoundModels, $model;
			     }
		     }
		 }
	}
	# sendmail

$to = 'kataeff@yandex.ru';
$from= 'parser@vseroboty.ru';
$reply_to = 'alexsakhnov@yandex.ru';
$subject='QRobot Parser Greets You!';
 
open(MAIL, "|/usr/sbin/sendmail -t");
 
## Mail Header
print MAIL "To: $to\n";
print MAIL "From: $from\n";
print MAIL "Subject: $subject\n\n";
## Mail Body
printf MAIL "\n===============\nUpdate complete!\nUpdated/total: %d/%d\n", $updated, scalar(@links)/2;
printf MAIL "\nNot found models: %s\n", join(", ", @notFoundModels);
close(MAIL);

#end sendamail

}
