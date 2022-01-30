# Pt.0 - 签到

参考 USTC Hackergame 2021 签到题

<https://github.com/USTC-Hackergame/hackergame2021-writeups/blob/master/official/%E7%AD%BE%E5%88%B0/README.md>

只要时间在 `1643558400 <= page <= 1643903999` 即可得到 flag

`HmacSha256(K, M) = SHA256(K⊕opad∣SHA256(K⊕ipad∣M))
flag = HmacSha256(secret, token)`

![接口](https://p.itxe.net/images/2022/01/30/image.png)
