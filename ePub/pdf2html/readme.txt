C:\>pdf2html.exe
PDF to HTML Converter Command Line v3.2
Web: http://www.verypdf.com
Web: http://www.verydoc.com
Email: support@verypdf.com
Release Date: Mar  7 2008
-------------------------------------------------------
Description:
  Convert Acrobat PDF files to HTML files.
Usage: pdf2html [options] <PDF-file> <HTML-file>
  -f <int>              : first page to convert
  -l <int>              : last page to convert
  -r <int>              : resolution, in DPI (default is 100)
  -quality <int>        : set quality for JPEG file, default is 95
  -imgformat <int>      : 0 is JPEG, 1 is PNG, 2 is GIF
  -opw <string>         : owner password (for encrypted PDF file)
  -upw <string>         : user password (for encrypted PDF file)
  -onehtm               : generate in one continuous html page
  -oneword              : create accurate HTML files
  -noimg                : remove images from HTML files
  -notxtidx             : remove text index file from HTML files
  -notextinbody         : remove text from HTML's body
  -notextinmeta         : remove text from HTML's meta
  -noseo                : don't optimize HTML files for search engines
  -homeurl <string>     : add a home URL into the left index page
  -yoffset <int>        : set Y offset for HTML page contents
  -noutf8               : remove UTF8 header to compatible with Firefox
  -$ <string>           : input your License Key
Examples:
  pdf2html.exe C:\in.pdf C:\out.htm
  pdf2html.exe -r 150 C:\in.pdf C:\out.htm
  pdf2html.exe -imgformat 1 C:\in.pdf C:\out.htm
  pdf2html.exe -noimg C:\in.pdf C:\out.htm
  pdf2html.exe -onehtm C:\in.pdf C:\out.htm
  pdf2html.exe -oneword C:\in.pdf C:\out.htm
  pdf2html.exe -homeurl "http://www.verypdf.com" C:\in.pdf C:\out.htm
  pdf2html.exe -yoffset 20 C:\in.pdf C:\out.htm
  pdf2html.exe -notextinbody -notextinmeta C:\in.pdf C:\out.htm

