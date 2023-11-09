#include<iostream>
#include<fstream>
#include<iomanip>
#include<cctype>
using namespace std;

long long getrev(long long x, long long p)
{
	long long ret = 1;
	long long b = p - 2;
	while (b)
	{
		if (b & 1) ret = ret * x % p;
		b >>= 1;
		x = x*x % p;
	}
	return ret;
}

long long e[50], a[50], b[50], c[50], d[50], x[50];
long long p[40010], cnt, low;
char str[10];

void getprime()
{
	for(int i = low + 1; i <= 1<<16; i++)
	{
		bool flag = 1;
		for(int j = 2; j*j <= i; j++)
		{
			if (i % j == 0) 
			{
				flag = 0;
				break;
			}
		}
		if (flag) p[++cnt] = i;
	}
}


int main()
{
	ifstream fin("input.txt");
	for (int i = 1; i <= 42; ++i)
	{
		cin >> e[i];
		low = max(low, e[i]);
	}
	getprime();
	for (int i = 1; i <= cnt; ++i)
	{
		bool flag = 1;
		const long long pp = p[i];
		for (int j = 42; j >= 2; --j)
		{
			d[j] = e[j] * getrev(e[j-1], pp) % pp;
		}
		for (int j = 42; j >= 3; --j)
		{
			c[j] = d[j] * getrev(d[j-1], pp) % pp;
		}
		for (int j = 42; j >= 4; --j)
		{
			b[j] = c[j] * getrev(c[j-1], pp) % pp;
		}
		for (int j = 42; j >= 5; --j)
		{
			a[j] = b[j] * getrev(b[j-1], pp) % pp;
		}
		for (int j = 42; j >= 6; --j)
		{
			x[j] = a[j] * getrev(a[j-1], pp) % pp;
			if (x[j] > 255 || x[j] < 0 || !isprint(x[j]))
			{
				flag = 0;
				break;
			}
		}
		if (flag)
		{
			for (int j = 6; j <= 42; ++j)
			{
				cout << char(x[j]);
			}
		}
	}
}
