#include <iostream>
using namespace std;

int main(){
    int n, t, result=0;
    cin>>n>>t;
    double matrix[n][2*n-1], partition=2.000;

    if(t==0){
        cout<<"0";
    }
    else if(t==2){
        cout<<"1";
    }
    else{
        for(int i=0; i<n; i++){
            for(int j=0; j<2*n-1; j++){
                matrix[i][j] = -1;
            }
        }

        int tmp = n-1;
        for(int i=0; i<n; i++){
            for(int j=tmp, k=0; k<=i; j+=2,k++){
                matrix[i][j]=0;
            }
            tmp--;
        }

        tmp = n-2;
        matrix[0][n-1] = double(t);

        for(int i=1; i<n; i++){
            for (int j=tmp,k=0; k<=i; j+=2,k++){
                if(k==0){
                    matrix[i][j] = (matrix[i-1][j+1]-1)/partition;
                }
                else if(k==i){
                    matrix[i][j] = (matrix[i-1][j-1]-1)/partition;
                }
                else{
                    matrix[i][j] = (matrix[i-1][j+1]-1 + matrix[i-1][j-1]-1)/partition;
                }

                if(matrix[i][j] >= 1){
                    result++;
                }
            }
            tmp--;
        }

        cout<<result+1;
    }

    return 0;
}
