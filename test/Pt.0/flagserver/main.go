package main

import (
	_ "embed"
	"net/http"
	"strconv"

	"2022.hello.shishengstore.com/pt0/flag"

	"github.com/gin-gonic/gin"
)

const (
	secret = "ecb20a14-be19-af4f-1a4e-f091b2427096"
)

//go:embed index.html
var index string

func main() {
	r := gin.Default()

	r.GET("/", func(c *gin.Context) {
		// workaround for avoid rendered
		c.Header("Content-Type", "text/html; charset=utf-8")
		c.String(http.StatusOK, index)
	})

	r.GET("/api/flag", func(c *gin.Context) {
		token := c.Query("token")
		page := c.Query("page")
		pageN, err := strconv.Atoi(page)
		if err != nil {
			c.JSON(http.StatusBadRequest, gin.H{
				"error": "invalid page",
			})
			return
		}

		if pageN < 1643558400 || pageN > 1643903999 {
			c.JSON(http.StatusForbidden, gin.H{
				"error": "wrong page",
			})
			return
		}

		sum := flag.Sum(token, secret)

		c.JSON(http.StatusOK, gin.H{
			"flag": sum,
		})
	})

	r.Run()
}
