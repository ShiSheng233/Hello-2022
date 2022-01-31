#include <bits/stdc++.h>
#define ll long long
using namespace std;
const ll mod=998244353;
ll dp[1000005][17];
int main() {
    freopen("data.in","w",stdout);
    vector<int>a={0,2022,996,251,114,514,1919,810893};
    dp[0][0]=1;
    int t=0;
    for(int i=1;i<a.size();i++){
        for(int j=1;j<=a[i];j++){
            t++;
            for(int v=0;v<=16;v++){
                for(int k=0;k*i+v<=16;k++){
                    (dp[t][k*i+v]+=dp[t-1][v])%=mod;
                }
            }
        }
    }
    for(int i=0;i<=16;i++){
        cout<<dp[t][i]<<' ';
    }cout<<'\n';
    return 0;
}
