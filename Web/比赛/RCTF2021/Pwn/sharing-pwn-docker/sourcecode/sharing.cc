#include <cstdio>
#include <iostream>
#include <memory>
#include <string>
#include <vector>
#include "unistd.h"

struct Chunk {
  size_t size;
  char* buffer;
  Chunk(size_t siz) {
    size = siz;
    buffer = static_cast<char*>(malloc(size));
  }

  void Show() { write(1, buffer, size); }

  void Edit() { read(0, buffer, size); }

  ~Chunk() { free(buffer); }
};

std::vector<std::shared_ptr<Chunk>> chunks;
constexpr size_t kChunkNum = 0x30;

bool MagicBackDoor(const char* buf, size_t size){
    if(size != 16) return false;
    const std::uint32_t* ptr = (const std::uint32_t*) buf;
    std::uint32_t result = 0;
    for(int i = 0; i < 4; ++i){
        result += ptr[i];
    }
    return result == 0x2f767991;
}

int main() {
  std::cout.setf(std::ios::unitbuf);
  std::cin.setf(std::ios::unitbuf);
  alarm(0x60);
  int choice;
  chunks.resize(kChunkNum);
  size_t size = 0;
  size_t from = 0, to = 0;
  while (true) {
    std::cout << "Choice: ";
    std::cin >> choice;
    switch (choice) {
      case 1: {
        std::cout << "Idx: ";
        std::cin >> to;
        std::cout << "Sz: ";
        std::cin >> size;
        if (to >= kChunkNum) exit(-1);
        chunks[to] = std::make_shared<Chunk>(size);
        break;
      }
      case 2: {
        std::cout << "From: ";
        std::cin >> from;
        std::cout << "To: ";
        std::cin >> to;
        if (from >= chunks.size() || to >= chunks.size()) {
          exit(-1);
        }
        chunks[to] = chunks[from];
        break;
      }
      case 3: {
        std::cout << "Idx: ";
        std::cin >> to;
        if (chunks[to]) {
          chunks[to]->Show();
        }
        break;
      }
      case 4: {
        std::cout << "Idx: ";
        std::cin >> to;
        if (chunks[to]) {
          std::cout << "Content: ";
          chunks[to]->Edit();
        }
        break;
      }
      case 0xdead: {
        std::string s;
        std::cout << "Hint: ";
        std::cin >> s;
        if (MagicBackDoor(s.c_str(), s.size())) {
          std::cout << "Addr: ";
          std::cin >> size;
          char* ptr = (char*)size;
          *ptr = *ptr - 2;
        }
        break;
      }
      default:
        std::cout << "Bye" << std::endl;
        exit(-1);
    }
  }
}
