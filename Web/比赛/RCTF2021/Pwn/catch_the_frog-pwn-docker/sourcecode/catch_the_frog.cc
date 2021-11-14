#include <unistd.h>
#include <array>
#include <cstdint>
#include <cstring>
#include <iostream>
#include <map>
#include <memory>
#include <sstream>
#include <string>
#include <vector>

#include <nop/serializer.h>
#include <nop/utility/die.h>
#include <nop/utility/stream_reader.h>
#include <nop/utility/stream_writer.h>
#include "actions.h"

constexpr size_t kMaxFeedTimes = 8;
class Frog {
 public:
  void Feed() {
    if (feed_times_ >= kMaxFeedTimes) {
      std::cout << "You cannot feed it anymore";
    }
    ++feed_times_;
  }

  void Name(const std::string& new_name) {
    size_t sz = new_name.size();
    if (sz > (name_sz_ + feed_times_)) {
      sz = name_sz_ + feed_times_;
    }

    memcpy(name_, new_name.c_str(), sz);
  }

  void Show() { std::cout << "Greeting from " << name_ << std::endl; }

  ~Frog() { free(name_); }

  Frog(size_t name_sz) {
    size_t alloc_sz = name_sz + 8;
    if (alloc_sz < 0x100) {
      name_ = static_cast<char*>(malloc(alloc_sz));
      name_sz_ = name_sz;
    } else {
      name_ = static_cast<char*>(malloc(0x100 - 8));
      name_sz_ = 0x100 - 8;
    }
    feed_times_ = 0;
  }

 private:
  char* name_;
  size_t name_sz_;
  size_t feed_times_;
};

namespace {
// Sends fatal errors to std::cerr.
auto Die() { return nop::Die(std::cerr); }

std::string ReadString() {
  size_t sz;
  std::cout << "Trying to receive a request, length: ";
  std::cin >> sz;
  if (sz > 0x300) {
    std::cout << "The request is too large" << std::endl;
    exit(-1);
  }

  std::cout << "Reading request: ";
  std::string result(sz, 0);
  read(0, result.data(), sz);

  return result;
}

}  // anonymous namespace

std::vector<std::unique_ptr<Frog>> cages;

std::optional<size_t> FindEmptySlot(
    const std::vector<std::unique_ptr<Frog>>& cage) {
  for (size_t i = 0; i < cage.size(); ++i) {
    if (cage[i] == nullptr) return i;
  }
  return std::nullopt;
}

int main(int, char**) {
  std::cout.setf(std::ios::unitbuf);
  std::cin.setf(std::ios::unitbuf);
  cages.resize(0x30);

  std::cout << "Welcome to the 'catch the frog' game! " << std::endl;
  std::cout << "I hope it will be more fun than capture the flag!" << std::endl;
  std::cout << "For security, we will encode the communication. And you should "
               "figure out the protocal we use."
            << std::endl;

  using Reader = nop::StreamReader<std::stringstream>;

  size_t sz;
  while (true) {
    std::string input_str = ReadString();
    if (input_str.empty()) break;

    nop::Deserializer<Reader> deserializer{input_str};
    Packet output_packet;
    deserializer.Read(&output_packet) || Die();

    switch (output_packet.cmd) {
      case Command::kCatch: {
        std::cout << "A lovely frog show up!";
        std::optional<size_t> empty_slot = FindEmptySlot(cages);
        if (!empty_slot.has_value()) {
          std::cout << "You have too many frogs!" << std::endl;
          continue;
        }
        cages[*empty_slot] = std::make_unique<Frog>(output_packet.size);
        std::cout << "Catched!" << std::endl;
        break;
      }
      case Command::kFeed: {
        if (cages[output_packet.index] == nullptr) {
          std::cout << "Feeding... air?" << std::endl;
        }
        cages[output_packet.index]->Feed();
        std::cout << "The little frog is happy!" << std::endl;
        break;
      }
      case Command::kFree: {
        if (cages[output_packet.index] == nullptr) {
          std::cout << "Ummm...?" << std::endl;
        }

        std::cout << "Good luck! " << std::endl;
        cages[output_packet.index] = nullptr;
        break;
      }
      case Command::kCheck: {
        if (cages[output_packet.index] == nullptr) {
          std::cout << "Ummm...?" << std::endl;
        }

        cages[output_packet.index]->Show();
        break;
      }
      case Command::kName: {
        if (cages[output_packet.index] == nullptr) {
          std::cout << "Ummm...?" << std::endl;
        }

        cages[output_packet.index]->Name(output_packet.name);
        std::cout << "The frog is exicited! " << std::endl;
        break;
      }
      default:
        std::cout << "Are you trying to hack me? Get out!" << std::endl;
        exit(-1);
    }
  }

  return 0;
}
