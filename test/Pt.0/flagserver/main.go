package main

import (
	"strconv"

	"2022.hello.shishengstore.com/pt0/flag"

	"github.com/gin-gonic/gin"
)

const (
	secret = "ecb20a14-be19-af4f-1a4e-f091b2427096"
)

func main() {
	r := gin.Default()
	r.GET("/flag", func(c *gin.Context) {
		token := c.Query("token")
		page := c.Query("page")
		pageN, err := strconv.Atoi(page)
		if err != nil {
			c.JSON(400, gin.H{
				"error": "invalid page",
			})
			return
		}

		if pageN < 1643558400 || pageN > 1643903999 {
			c.JSON(403, gin.H{
				"error": "wrong page",
			})
			return
		}

		sum := flag.Sum(token, secret)

		c.JSON(200, gin.H{
			"flag": sum,
		})
	})
	r.Run()
}
