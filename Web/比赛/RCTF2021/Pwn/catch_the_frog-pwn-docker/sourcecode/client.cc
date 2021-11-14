#include <unistd.h>
#include <array>
#include <cstdint>
#include <cstring>
#include <iostream>
#include <map>
#include <sstream>
#include <string>
#include <vector>

#include <nop/serializer.h>
#include <nop/utility/die.h>
#include <nop/utility/stream_reader.h>
#include <nop/utility/stream_writer.h>
#include "actions.h"

namespace {
using std::cin;
using std::cout;
using std::endl;

std::string ReadString() {
  size_t sz;
  std::cout << "Length: ";
  std::cin >> sz;
  if (sz > 0x1000) {
    std::cout << "The length is too large" << std::endl;
    exit(-1);
  }

  std::cout << "Content: ";
  std::string result(sz, 0);
  unsigned char c;
  for (size_t i = 0; i < sz; i++) {
    read(0, &c, 1);
    result[i] = c;
  }

  return result;
}

void Menu() {
  cout << "1. Allocate\n";
  cout << "2. Feed\n";
  cout << "3. Show\n";
  cout << "4. Delete\n";
  cout << "5. Name\n";
  cout << "6. Exit\n";
  cout << "Choice: ";
}

Packet GeneratePacket() {
  Packet packet;
  if (true) {
    Menu();
    size_t choice;
    cin >> choice;
    switch (choice - 1) {
      case 0:
        packet.cmd = Command::kCatch;
        std::cout << "Size: ";
        cin >> packet.size;
        break;
      case 1:
        packet.cmd = Command::kFeed;
        std::cout << "Index: ";
        cin >> packet.index;
        break;
      case 2:
        packet.cmd = Command::kCheck;
        std::cout << "Index: ";
        cin >> packet.index;
        break;
      case 3:
        packet.cmd = Command::kFree;
        std::cout << "Index: ";
        cin >> packet.index;
        break;
      case 4:
        packet.cmd = Command::kName;
        std::cout << "Index: ";
        cin >> packet.index;
        packet.name = ReadString();
        break;
      default:
        exit(0);
    }
  }
  cout << "Done" << std::endl;
  return packet;
}

// Sends fatal errors to std::cerr.
auto Die() { return nop::Die(std::cerr); }

}  // anonymous namespace

int main(int, char**) {
  std::cout.setf(std::ios::unitbuf);
  std::cin.setf(std::ios::unitbuf);

  // Create a serializer around a std::stringstream.
  using Writer = nop::StreamWriter<std::stringstream>;

  // Write some data types to the stream.

  // Create a deserializer around a std::stringstream with the serialized data.
  // std::cout << ss << std::endl;

  // Read some data types from the stream.
  while (true) {
    Packet packet = GeneratePacket();
    nop::Serializer<Writer> serializer;
    serializer.Write(packet) || Die();
    std::cout << "Generated packet size: "
              << serializer.writer().stream().str().size() << std::endl;
    std::cout << "Content: " << serializer.writer().stream().str();
  }

  return 0;
}
