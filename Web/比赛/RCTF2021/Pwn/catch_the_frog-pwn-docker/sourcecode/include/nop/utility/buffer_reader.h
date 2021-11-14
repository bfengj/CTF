/*
 * Copyright 2017 The Native Object Protocols Authors
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

#ifndef LIBNOP_INCLUDE_NOP_UTILITY_BUFFER_READER_H_
#define LIBNOP_INCLUDE_NOP_UTILITY_BUFFER_READER_H_

#include <cstddef>
#include <cstdint>
#include <cstring>

#include <nop/base/encoding.h>
#include <nop/base/utility.h>

namespace nop {

// An efficient reader type that supports runtime serialization from a byte
// buffer. This reader improves efficiency by only performing bounds checks in
// the Ensure() method. This type is safe for use with the library-provided
// Deserializer types, which predicate serialization on the result of Ensure().
// Use PedanticBufferReader if your use case interacts with the reader directly
// and you need bounds checking in the Read() and Skip() methods.
class BufferReader {
 public:
  BufferReader() = default;
  BufferReader(const BufferReader&) = default;
  template <std::size_t Size>
  BufferReader(const std::uint8_t (&buffer)[Size])
      : buffer_{buffer}, size_{Size} {}
  BufferReader(const std::uint8_t* buffer, std::size_t size)
      : buffer_{buffer}, size_{size} {}
  BufferReader(const void* buffer, std::size_t size)
      : buffer_{static_cast<const std::uint8_t*>(buffer)}, size_{size} {}

  BufferReader& operator=(const BufferReader&) = default;

  Status<void> Ensure(std::size_t size) {
    if (size_ - index_ < size)
      return ErrorStatus::ReadLimitReached;
    else
      return {};
  }

  Status<void> Read(std::uint8_t* byte) { return Read(byte, byte + 1); }

  template <typename T, typename Enable = EnableIfArithmetic<T>>
  Status<void> Read(T* begin, T* end) {
    const std::size_t element_size = sizeof(T);
    const std::size_t length = end - begin;
    const std::size_t length_bytes = length * element_size;

    std::memcpy(begin, &buffer_[index_], length_bytes);
    index_ += length_bytes;
    return {};
  }

  Status<void> Skip(std::size_t padding_bytes) {
    index_ += padding_bytes;
    return {};
  }

  bool empty() const { return index_ == size_; }

  std::size_t remaining() const { return size_ - index_; }
  std::size_t capacity() const { return size_; }

 private:
  const std::uint8_t* buffer_{nullptr};
  std::size_t size_{0};
  std::size_t index_{0};
};

}  // namespace nop

#endif  // LIBNOP_INCLUDE_NOP_UTILITY_BUFFER_READER_H_
