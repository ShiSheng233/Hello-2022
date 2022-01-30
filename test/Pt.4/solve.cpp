#include <bits/stdc++.h>
using namespace std;
using ll = long long;
using poly = vector<ll>;

const ll mod = 998244353;
const int N = 1005;

poly mu={0,1,mod-1,mod-1,0,mod-1,1,mod-1,0,0,1,mod-1,0,mod-1,1,1,0};
ll inv[N],fiv[N],fc[N];
inline ll Ksm(ll x,ll y){
    ll tmp=1;
    while(y){
        if(y&1)tmp=tmp*x%mod;
        y>>=1;if(y)x=x*x%mod;
    }return tmp;
}
inline ll C(ll x,ll y){
    return fc[x]*fiv[y]%mod*fiv[x-y]%mod;
}
inline poly operator*(poly A,poly B){
    poly C(17);
    for(int i=0;i<=16;i++){
        for(int j=0;j<=i;j++){
            (C[i]+=A[j]*B[i-j])%=mod;
        }
    }return C;
}
inline poly operator+(poly A,poly B){
    poly C(17);
    for(int i=0;i<=16;i++)C[i]=(A[i]+B[i])%mod;
    return C;
}
inline poly T(poly A,int k,int i){
    poly B(17);
    if(i==0){
        B[0]=1;return B;
    }
    for(int j=0;j*k<=16;j++)B[j*k]=A[j];
    poly C(17);C[0]=1;
    A=C;
    while(i--)A=A*B;
    return A;
}
int main(){
    freopen("data.in","r",stdin);
    poly F(17);
    for(int i=0;i<=16;i++)cin>>F[i];
    assert(mu.size()==17);
    inv[0]=fiv[0]=fc[0]=1;
    for(int i=1;i<=16;i++){
        fc[i]=fc[i-1]*i%mod;
        inv[i]=Ksm(i,mod-2);
        fiv[i]=fiv[i-1]*inv[i]%mod;
    }
    poly ans(17);
    for(int k=1;k<=16;k++){
        ll t=(mod-1)*mu[k]%mod*inv[k]%mod;
        for(int j=1;j<=16;j++){
            ll t2=t*inv[j]%mod;
            for(int i=0;i<=j;i++){
                ll t3=t2*C(j,i)%mod;
                if(i&1)t3=t3*(mod-1)%mod;
                poly t4(17);t4[0]=t3;
                ans=ans+(t4*T(F,k,i));
            }
        }
    }
    ll su=0;
    for(int i=1;i<=16;i++){
        su^=ans[i];
        cout<<ans[i]<<' ';
    }cout<<'\n';
    
    cout<<su<<'\n';
    return 0;
}
