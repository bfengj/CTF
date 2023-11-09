# searchsploit

```shell
Usage: searchsploit [options] term1 [term2] ... [termN]
 
==========
 Examples
==========
  searchsploit afd windows local
  searchsploit -t oracle windows
  searchsploit -p 39446
  searchsploit linux kernel 3.2 --exclude="(PoC)|/dos/"
 
  For more examples, see the manual: https://www.exploit-db.com/searchsploit/
 
=========
 Options
=========
   -c, --case     [Term]      区分大小写(默认不区分大小写)
   -e, --exact    [Term]      对exploit标题进行EXACT匹配 (默认为 AND) [Implies "-t"].
   -h, --help                 显示帮助
   -j, --json     [Term]      以JSON格式显示结果
   -m, --mirror   [EDB-ID]    把一个exp拷贝到当前工作目录,参数后加目标id
   -o, --overflow [Term]      Exploit标题被允许溢出其列
   -p, --path     [EDB-ID]    显示漏洞利用的完整路径（如果可能，还将路径复制到剪贴板），后面跟漏洞ID号
   -t, --title    [Term]      仅仅搜索漏洞标题（默认是标题和文件的路径）
   -u, --update               检查并安装任何exploitdb软件包更新（deb或git）
   -w, --www      [Term]      显示Exploit-DB.com的URL而不是本地路径（在线搜索）
   -x, --examine  [EDB-ID]    使用$ PAGER检查（副本）Exp
       --colour               搜索结果不高亮显示关键词
       --id                   显示EDB-ID
       --nmap     [file.xml]  使用服务版本检查Nmap XML输出中的所有结果（例如：nmap -sV -oX file.xml）
                                使用“-v”（详细）来尝试更多的组合
       --exclude="term"       从结果中删除值。通过使用“|”分隔多个值
                              例如--exclude=“term1 | term2 | term3”。
 
=======
 Notes
=======
 * 你可以使用任意数量的搜索词。
 * Search terms are not case-sensitive (by default), and ordering is irrelevant.
   * 搜索术语不区分大小写(默认情况下)，而排序则无关紧要。
   * 如果你想用精确的匹配来过滤结果，请使用用 -e 参数
 * 使用' - t '将文件的路径排除，以过滤搜索结果
   * 删除误报(特别是在搜索使用数字时 - i.e. 版本).
 * 当更新或显示帮助时，搜索项将被忽略。
```

