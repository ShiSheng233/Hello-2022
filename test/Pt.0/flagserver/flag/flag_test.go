package flag_test

import (
	"testing"

	. "2022.hello.shishengstore.com/pt0/flag"
	"github.com/google/go-cmp/cmp"
)

func TestSum(t *testing.T) {
	token := "bc421843-d2dd-772f-29ce-4fd88db6efe0"
	secret := "e168e7f5-4f9d-94c3-8534-a45d11b54d6f"
	sum := "75b26267a7d1cfecd4f8aa0702871f27fcdb54bde52b306b233bbf5ae8b65ed0"

	if r := cmp.Diff(Sum(token, secret), sum); r != "" {
		t.Error(r)
	}
}
