// docker run -i --rm -v "$PWD":/judge -w /judge gcc:4.9 g++ main.cpp -o main -O2 -Wall -lm --static 2>error.txt
// docker run -i --rm -v "$PWD":/judge -w /judge gcc:4.9 ./main<in.txt >out.txt 2>error.txt

// g++ main.cpp -o main -O2 -Wall -lm --static

#include <iostream>
#include <vector>
using namespace std;

int main() {
	int i, j, cnt = 0, n;
	cin >> n;

	vector<bool> v(n + 1, false);
	for (i = 2; i <= n; i++) if (!v[i]) {
		cnt++;
		// cout << i << endl;
		for (j = i + i; j <= n; j += i) 
			v[j] = 1;
	}
	cout << cnt << endl;
	
	return 0;
}


