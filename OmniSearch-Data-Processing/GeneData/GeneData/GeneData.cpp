#include <iostream>
#include <fstream>
#include <string>
#include <vector>
#include <sstream>
#include <algorithm>
#include <map>

using namespace std;

int main(int argc, char* argv[])
{
	map<string, string> mirna_map;
	map<string, string> symbol_map;
	string col1, col2, col3, col4;

	ofstream out("gene_data.ttl");
	if (!out.is_open()) {
		cout << "Failed to create output file!\n";
		system("pause");
		return 0;
	}

	ifstream in("miRNA_data.txt");
	if (!in.is_open()) {
		cout << "File not found!\n";
		system("pause");
		return 0;
	}

	while (in >> col1 >> col2) {
		mirna_map[col1] = col2;
		out << col2 << "\trdfs:subClassOf\t" << "<http://purl.obolibrary.org/obo/NCRO_0000810> .\n"
			<< col2 << "\trdfs:label\t\"" << col1 << "\" .\n";
	}

	in.close();

	in = ifstream("mirdb_data.txt");
	if (!in.is_open()) {
		cout << "File not found!\n";
		system("pause");
		return 0;
	}

	int i = 10000;
	while (in >> col1 >> col2 >> col3 >> col4) {
		if (symbol_map.count(col3) == 0) {
			symbol_map[col3] = "<http://purl.obolibrary.org/obo/OMIT_00" + to_string(i) + ">";
			++i;

			out << symbol_map.at(col3) << "\trdfs:subClassOf\t<http://purl.obolibrary.org/obo/NCRO_0000025> .\n"
				<< symbol_map.at(col3) << "\trdfs:label\t\"" << col3 << "\" .\n"
				<< symbol_map.at(col3) << "\t<http://purl.obolibrary.org/obo/OMIT_0000095>\t\"" << col2 << "\" .\n";
		}

		out << mirna_map.at(col1) << "\tncro:_has_predicted_target\t" << symbol_map.at(col3) << " .\n"
			<< "<http://purl.obolibrary.org/obo/OMIT_0000020>\t<http://purl.obolibrary.org/obo/RO_0000057>\t" << symbol_map.at(col3) << " .\n";
	}

	in.close();

	in = ifstream("targetscan_data.txt");
	if (!in.is_open()) {
		cout << "File not found!\n";
		system("pause");
		return 0;
	}

	while (in >> col1 >> col2 >> col3 >> col4) {
		if (symbol_map.count(col3) == 0) {
			symbol_map[col3] = "<http://purl.obolibrary.org/obo/OMIT_00" + to_string(i) + ">";
			++i;

			out << symbol_map.at(col3) << "\trdfs:subClassOf\t<http://purl.obolibrary.org/obo/NCRO_0000025> .\n"
				<< symbol_map.at(col3) << "\trdfs:label\t\"" << col3 << "\" .\n"
				<< symbol_map.at(col3) << "\t<http://purl.obolibrary.org/obo/OMIT_0000095>\t\"" << col2 << "\" .\n";
		}

		out << mirna_map.at(col1) << "\tncro:_has_predicted_target\t" << symbol_map.at(col3) << " .\n"
			<< "<http://purl.obolibrary.org/obo/OMIT_0000019>\t<http://purl.obolibrary.org/obo/RO_0000057>\t" << symbol_map.at(col3) << " .\n";
	}

	out.close();
	in.close();
	system("pause");
	return 0;
}