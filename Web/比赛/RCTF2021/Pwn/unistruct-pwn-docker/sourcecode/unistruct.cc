#include <cstdio>
#include <iostream>
#include <memory>
#include <string>
#include <variant>
#include <vector>
#include "unistd.h"

using MyType =
    std::variant<std::monostate, int, float, std::string, std::vector<unsigned int>>;

std::vector<MyType> chunks;
constexpr size_t kChunkNum = 0x10;

void Menu() {
  std::cout << "1. Alloc" << std::endl;
  std::cout << "2. Edit" << std::endl;
  std::cout << "3. Show" << std::endl;
  std::cout << "4. Free" << std::endl;
  std::cout << "5. Exit" << std::endl;
  std::cout << "Choice: ";
}

void Free() {
  size_t index;
  std::cout << "Index: ";
  std::cin >> index;
  if (index >= kChunkNum) {
    return;
  }

  chunks[index] = std::monostate{};
}

void Edit() {
  size_t index;
  std::cout << "Index: ";
  std::cin >> index;
  if (index >= kChunkNum) {
    return;
  }
  size_t idx = chunks[index].index();
  switch (idx) {
    case 1:
      std::cin >> std::get<1>(chunks[index]);
      break;
    case 2:
      std::cin >> std::get<2>(chunks[index]);
      break;
    case 3:
      std::cin >> std::get<3>(chunks[index]);
      break;
    case 4: {
      auto vec = std::get<4>(chunks[index]);
      int in_place;
      unsigned int new_value = 0;
      for (auto iter = vec.begin(); iter != vec.end(); ++iter) {
        std::cout << "Old value: " << *iter << std::endl;
        std::cout << "Append or in place, 1 for in place: ";
        std::cin >> in_place;
        std::cout << "New value: ";
        std::cin >> new_value;
        if (new_value == 0xcafebabe) break;
        if (in_place) {
          *iter = new_value;
        } else {
          vec.push_back(new_value);
          iter--;
        }
      }
      break;
    }
    default:
      break;
  }
}

void Show() {
  size_t index;
  std::cout << "Index: ";
  std::cin >> index;
  if (index >= kChunkNum) {
    return;
  }
  size_t idx = chunks[index].index();
  switch (idx) {
    case 1:
      std::cout << std::get<1>(chunks[index]) << std::endl;
      break;
    case 2:
      std::cout << std::get<2>(chunks[index]) << std::endl;
      break;
    case 3:
      std::cout << std::get<3>(chunks[index]) << std::endl;
      break;
    case 4: {
      auto vec = std::get<4>(chunks[index]);
      for (unsigned int i : vec) {
        std::cout << i << ", ";
      }
      std::cout << std::endl;
      break;
    }
    default:
      break;
  }
}

void Alloc() {
  size_t index;
  std::cout << "Index: ";
  std::cin >> index;
  if (index >= kChunkNum) {
    return;
  }

  size_t type;
  std::cout << "Type: ";
  std::cin >> type;
  std::cout << "Value: ";
  switch (type) {
    case 1: {
      int int_value = 0;
      std::cin >> int_value;
      chunks[index].emplace<int>(int_value);
      break;
    }
    case 2: {
      float float_value = 0.0;
      std::cin >> float_value;
      chunks[index].emplace<float>(float_value);
      break;
    }
    case 3: {
      std::string string_value;
      std::cin >> string_value;
      chunks[index].emplace<std::string>(string_value);
      break;
    }
    case 4: {
      size_t sz = 0;
      std::cin >> sz;
      chunks[index].emplace<std::vector<unsigned int>>(sz, 0);
      break;
    }
    default:
      return;
  }
}
int main() {
  alarm(0x60);
  std::cout.setf(std::ios::unitbuf);
  std::cin.setf(std::ios::unitbuf);
  std::cout << "Thanks for using my super powerful data structure, which can "
               "hold anything that you can image."
            << std::endl;
  int choice;
  chunks.resize(kChunkNum);
  size_t index;
  while (true) {
    Menu();
    std::cin >> choice;
    switch (choice) {
      case 1: {
        Alloc();
        break;
      }
      case 2: {
        Edit();
        break;
      }
      case 3: {
        Show();
        break;
      }
      case 4: {
        Free();
        break;
      }
      default:
        std::cout << "Invalid input" << std::endl;
        exit(-1);
    }
  }
}
