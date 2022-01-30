package flag

import (
	"crypto/hmac"
	"crypto/sha256"
	"encoding/hex"
)

func Sum(token string, secret string) string {
	h := hmac.New(sha256.New, []byte(secret))
	h.Write([]byte(token))

	return hex.EncodeToString(h.Sum(nil))
}
